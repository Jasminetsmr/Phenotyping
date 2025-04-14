# Deklarasi import modul untuk menggunakan modul yang terinstall pada python
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
  database="ds_apoy"
)

mycursor = mydb.cursor(buffered=True)
 
# Fungsi Proses untuk memproses image hasil potretan
def Proses():

    # Mendefinisikan untuk kebutuhan nama file
    now = datetime.datetime.now()
    waktu = now.strftime("%H%M%S")
    tanggal = now.strftime("%Y%m%d")

    data_waktu = now.strftime("%H:%M:%S")
    data_tanggal = now.strftime("%Y-%m-%d")
    
    tanaman = sys.argv[1]
    blok = sys.argv[2]
    tray = sys.argv[3]
    ketinggian = sys.argv[4]
    tanggal = sys.argv[5]
    waktu = sys.argv[6]
    file = sys.argv[7]

    global img_ori
    img_nama =  tanaman + "-" + blok + tray + "-" + str(tanggal) + str(waktu) + ".jpg"
    folder_ori = './citra/'
    img_ori = folder_ori + img_nama

    # Memotret dan menyimpan gambar original
    cv2.imwrite(img_ori, file)
    
    # Mengubah format gambar dari RGB menjadi HSV
    # img_nama = "bulet"
    img = cv2.imread(img_ori)
    hsv = cv2.cvtColor(img, cv2.COLOR_BGR2HSV) 
    
    # Thresholding dan Masking menggunakan pembatasan nilai atas dan bawah HSV
    # Nilai disesuaikan dengan nilai threshold objek
    lower_green = np.array([0,0,0])
    upper_green = np.array([225,255,40]) 
        
    mask = cv2.inRange(hsv, lower_green, upper_green)
    res  = cv2.bitwise_and(img,img, mask= mask)
    count = cv2.countNonZero(mask)
    
    # Menyimpan gambar hasil processing
 
    folder_thresh = './citra/threshold/'
    folder_mask = './citra/bitwise/'
    
    img_thresh = folder_thresh + img_nama 
    img_bitwise = folder_mask + img_nama 
    
    cv2.imwrite(img_thresh, mask)
    cv2.imwrite(img_bitwise, res)  

    # Menghitung masing-masing komponen warna BGR
    citra = res
    avg_color_per_row = np.average(citra, axis=0)
    avg_color = np.average(avg_color_per_row, axis=0)
    sum_color_per_row = np.sum(citra, axis=0)
    sum_color = np.sum(sum_color_per_row, axis=0)
    
    # Menghitung jumlah pixel
    pixel=str(count)
    t1=str(f'{pixel:.2f}')

    # Menghitung Luas kanopi daun dari jumlah piksel
    luas=0.0141*count-0.188 # Persamaan didapat dari kalibrasi piksel kamera 
    t2=str(f'{luas:.2f}')
    
    # Menghitung nilai B,G dan R
    B=((sum_color/count)[0])
    G=((sum_color/count)[1])
    R=((sum_color/count)[2])
    
    t3 = str(avg_color)
    t4 = str(sum_color)
    
    # Menghitung nilai image processing
    ExG = 2 * G - R - B
    ExR = 1.4 * R - G
    ExGR = 3 * G - 2.4 * R - B
    NGRDI = (G - R) / (G + R)

    t5 = str(ExG)
    t6 = str(ExR)
    t7 = str(ExGR)
    t8 = str(NGRDI)

    # Grey Scale dan Luminosity
    grey = (B + G + R) / 3
    lum = 0.299*R + 0.587*G + 0.114*B
    
    t9=str(f'{grey:.4f}')
    t10=str(f'{lum:.2f}')

    
    print(img_nama + ": " + t1)
    # Menginput hasil image processing kedalam database
    sql = "INSERT INTO data (tanggal, waktu, nama, pixel, luas, rataan_rgb, exg, exr, exgr, ngrdi, greyscale, luminosity) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
    val = (data_tanggal, data_waktu, img_nama, t1, t2, t3, t5, t6, t7, t8, t9, t10)

    mycursor.execute(sql, val)
    mydb.commit()

# Pemanggilan Fungsi Cam1 dan Cam2 untuk pemotretan
Proses()