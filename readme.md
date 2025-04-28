Cara menjalankan project mode development:
1. npm run dev
2. php artisan migrate (jika belum)
3. php artisan db:seed untuk Sampling data (jika belum)
4. matikan semua komentar bagian dev di .env
5. jadikan komentar semua bagian prod di .env
6. php artisan serve

Cara menjalankan project mode production:
1. npm run prod
2. php artisan migrate (jika belum)
3. php artisan db:seed untuk Sampling data (jika belum)
4. jadikan komentar bagian dev di .env
5. matikan semua komentar bagian prod di .env
6. pada bagian APP_URL disesuaikan dengan domain yang akan digunakan
6. php artisan serve