import cv2
import numpy as np
import datetime
import mysql.connector
import sys

# Deklarasi mysql.connector untuk menyambungkan GUI dengan database
mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  password="",
  database="jasmine"
)

mycursor = mydb.cursor(buffered=True)
 
# def cek_jumlah_kamera():
#     index = 0
#     kamera_tersedia = []
#     while True:
#         cap = cv2.VideoCapture(index)
#         if not cap.read()[0]:
#             break
#         else:
#             kamera_tersedia.append(index)
#         cap.release()
#         index += 1
#     return kamera_tersedia

# Fungsi Proses untuk memproses image hasil potretan
def Proses():
    cam1 = cv2.VideoCapture(0) 
    _, frame = cam1.read()
    cam1.release()

    # Mendefinisikan untuk kebutuhan nama file
    now = datetime.datetime.now()
    waktu = now.strftime("%H%M%S")
    tanggal = now.strftime("%Y%m%d")

    data_waktu = now.strftime("%H:%M:%S")
    data_tanggal = now.strftime("%Y-%m-%d")
    
    tanaman = "1" 
    blok = "a"
    tray = "12"

    img_nama = tanaman + "-" + blok + tray + "-" + str(tanggal) + str(waktu) + ".jpg"
    folder_ori = './citra/'
    img_ori = folder_ori + img_nama

    # Memotret dan menyimpan gambar original
    cv2.imwrite(img_ori, frame)
    
    # Mengubah format gambar dari RGB menjadi HSV
    img = cv2.imread(img_ori)
    hsv = cv2.cvtColor(img, cv2.COLOR_BGR2HSV) 
    
    # Thresholding dan Masking menggunakan pembatasan nilai atas dan bawah HSV
    lower_green = np.array([0,0,0])
    upper_green = np.array([225,255,40]) 
        
    mask = cv2.inRange(hsv, lower_green, upper_green)
    res  = cv2.bitwise_and(img, img, mask=mask)
    
    # Menyimpan gambar hasil processing
    folder_thresh = './citra/threshold/'
    folder_mask = './citra/bitwise/'
    
    img_thresh = folder_thresh + img_nama 
    img_bitwise = folder_mask + img_nama 
    
    cv2.imwrite(img_thresh, mask)
    cv2.imwrite(img_bitwise, res)  
    
    # Menginput hasil image processing ke dalam database
    sql = "INSERT INTO ds_foto (nama_foto, waktu_foto) VALUES (%s, %s)"
    val = (img_nama, data_tanggal + " " + data_waktu)

    mycursor.execute(sql, val)
    mydb.commit()

# kamera_aktif = cek_jumlah_kamera()

# Menjalankan fungsi Proses() sebanyak 5 kali
for i in range(5):
    print(f"Mengambil gambar {i+1}/5...")
    Proses()
    print("Gambar berhasil diambil dan diproses.")

