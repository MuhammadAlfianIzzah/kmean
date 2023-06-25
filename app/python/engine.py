import sys
import json
import time
import warnings
from datetime import datetime
import requests
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
from sklearn.cluster import KMeans
from sklearn import datasets
import os
from pathlib import Path
from dotenv import load_dotenv
dotenv_path = Path('../../.env')
load_dotenv(dotenv_path=dotenv_path)
BASE_URL = os.getenv('BASE_URL')
PATH_IMAGE = os.getenv('PATH_IMAGE')

os.environ["OMP_NUM_THREADS"] = "1"
response = requests.get(BASE_URL+"api/transaksi")
data = response.json()["data"]

if (len(sys.argv) <= 3):
    print("Argumen tidak lengkap")
    exit()

engineId = sys.argv[1]
nClusters = sys.argv[2]
maxLiterasi = sys.argv[3]


def runningAnalisis(data, engineId, nClusters, nInit):
    headers = {
        'User-Agent': 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36'}
    iris = pd.DataFrame.from_dict(data)
    x = iris.iloc[:, [1, 2, 3]]
    x = np.array(x)
    filename = datetime.now().strftime("%Y%m%d-%H%M%S")
    # Collecting the distortions into list
    os.environ["OMP_NUM_THREADS"] = "1"
    warnings.filterwarnings('ignore')
    distortions = []
    K = range(1, 10)
    for k in K:
        kmeanModel = KMeans(n_clusters=k, n_init=int(nInit))
        kmeanModel.fit(x)
        distortions.append(kmeanModel.inertia_)
    # Plotting the distortions
    plt.figure(figsize=(16, 8))
    plt.plot(K, distortions, "bx-")
    plt.xlabel("k")
    plt.ylabel("Distortion")
    plt.title("The Elbow Method showing the optimal cluster")

    filename = datetime.now().strftime("%Y%m%d-%H%M%S")

    # Remove non-alphanumeric characters
    clean_filename = ''.join(e for e in filename if e.isalnum())
    path = os.path.join(PATH_IMAGE, clean_filename + ".png")
    plt.savefig(path)

    kmeans_model = KMeans(n_clusters=int(nClusters),
                          n_init=int(nInit), random_state=32932)
    kmeans_predict = kmeans_model.fit_predict(x)
    iris['Cluster'] = kmeans_predict

    for ind in range(len(iris)):
        try:
            url = BASE_URL+'api/history-engine'
            myobj = {
                'kode': str(iris.iloc[ind]['kode']),
                'nama_barang': str(iris.iloc[ind]['nama_barang']),
                'stok_awal': int(iris.iloc[ind]['stok_awal']),
                'ttl_penjualan': int(iris.iloc[ind]['ttl_penjualan']),
                'stok_akhir': int(iris.iloc[ind]['stok_akhir']),
                'cluster': int(iris.iloc[ind]['Cluster']),
                'engine_id': engineId
            }
            x = requests.post(url, json=myobj, headers=headers)
            print(x)
            time.sleep(1)
        except requests.exceptions.HTTPError as err:
            print(err)
    #         raise SystemExit(err)
    print("oke")

    # colors = ["red", "blue", "green", "cyan", "black",
    #           "magenta", "violet", "red", "red", "red"]
    # for i in range(int(nClusters)):
    #     plt.scatter(x[kmeans_predict == i, 0], x[kmeans_predict == i,
    #                 1], s=100, c=colors[i], label="cluster"+str(i))

    # plt.scatter(kmeans_model.cluster_centers_[:, 0], kmeans_model.cluster_centers_[
    #             :, 1], s=100, c="yellow", label="Centroids")
    # filename = datetime.now().strftime("%Y%m%d-%H%M%S")
    # # Remove non-alphanumeric characters
    # scatter = ''.join(e for e in filename if e.isalnum())
    # path = os.path.join(PATH_IMAGE, scatter + ".png")
    # plt.savefig(path)

    try:
        url = BASE_URL+'api/engine/'+str(engineId)+"/update"

        myobj = {
            "elbow": clean_filename+".png",
            "finish_at": str(datetime.now())
        }
        x = requests.post(url, json=myobj, headers=headers)
        print(x)
        print(url)
        time.sleep(1)
    except requests.exceptions.HTTPError as err:
        print(err)


runningAnalisis(data, engineId, nClusters, maxLiterasi)
