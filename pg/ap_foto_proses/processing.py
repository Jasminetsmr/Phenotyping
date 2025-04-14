# Mendeklarasikan import modul untuk menggunakan modul yang terinstall pada python
import cv2
import numpy as np
import datetime
import mysql.connector
import sys

# Mendeklarasikan mysql.connector untuk menyambungkan GUI dengan database
mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  password="",
  database="ds_poy"
)

mycursor = mydb.cursor(buffered=True)

# Mendeklarasikan kamera 
file = sys.argv[7]
frame = cv2.imread(file)

# Mendeklasasikan data waktu 
waktu = sys.argv[5] 
tanggal = sys.argv[6]

# Mengambil nilai dari website dan database untuk kebutuhan input
kamera = "1"
tanaman = "cabairawit"
blok = "A"
tray = "3"

sql_layout = "SELECT id_layout FROM `ds_layout` WHERE blok = %s AND tray = %s"
val_layout = (blok, tray)
mycursor.execute(sql_layout,val_layout)
result = mycursor.fetchone()
layout = str(result[0])

# Mendefinisikan untuk kebutuhan nama file
global img_ori
img_nama =  tanaman + "-" + blok + tray + "-" + str(tanggal) + str(waktu) + ".jpg"
folder_ori = 'C:/xampp/htdocs/ds_poy/citra/'
img_ori = folder_ori + img_nama

# Memotret dan menyimpan gambar original
cv2.imwrite(img_ori, frame)

# Mengubah format gambar dari RGB menjadi HSV
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

folder_thresh = 'C:/xampp/htdocs/ds_poy/citra/threshold/'
folder_mask = 'C:/xampp/htdocs/ds_poy/citra/bitwise/'

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
t1=(f'{count:.2f}')

# Menghitung Luas kanopi daun dari jumlah piksel
luas=0.0141*count-0.188 # Persamaan didapat dari kalibrasi piksel kamera 
t2=(f'{luas:.2f}')

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

# Mereset query database
mycursor.fetchall()

# Menginput hasil image processing kedalam database
sql = "INSERT INTO ds_foto (id_layout, id_kamera, tanggal_foto, waktu_foto, nama_foto, jumlah_pixel, luas_kanopi, rataan_rgb, exg, exr, exgr, ngrdi, greyscale, luminosity) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"
val = (layout, kamera, tanggal, waktu, img_nama, t1, t2, t3, t5, t6, t7, t8, t9, t10)

mycursor.execute(sql, val)
mydb.commit()

# Menandakan akhir script
print("end")
