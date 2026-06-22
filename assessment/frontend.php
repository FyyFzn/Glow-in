<?php
// File Frontend dengan ekstensi .php
// Catatan: Walaupun berformat PHP, penarikan data ke API 
// TETAP menggunakan JavaScript (Fetch) untuk memenuhi syarat 
// "1 File Frontend yang terdapat JS untuk mengeksekusi API PHP".
?>
<!DOCTYPE html>
<html>
<head>
    <title>Frontend API Consumer</title>
</head>
<body>
    <h1>API Manajemen Postingan (Frontend PHP & JS)</h1>
    
    <label>Masukkan API Key Anda:</label>
    <input type="text" id="api_key">
    <button onclick="muatPosts()">Load Data (GET)</button>
    <br><br>

    <hr>
    
    <!-- FORM TAMBAH / EDIT POSTINGAN -->
    <h3>Form Postingan</h3>
    
    <!-- Input tersembunyi untuk menyimpan ID jika sedang mode edit -->
    <input type="hidden" id="post_id"> 
    
    <label>Isi Postingan:</label><br>
    <textarea id="post_content" rows="4" cols="50"></textarea><br><br>
    
    <button onclick="simpanPost()">Simpan Data</button>
    <button onclick="batalEdit()">Batal Edit</button>

    <hr>
    
    <!-- TEMPAT MENAMPILKAN DATA -->
    <h3>Daftar Postingan</h3>
    <ul id="daftar_posts"></ul>

    <!-- SCRIPT JAVASCRIPT (Syarat Wajib Assessment) -->
    <script>
        // Memanggil API Backend PHP
        const baseUrl = 'api_endpoints.php';

        // 1. READ (Ambil Semua Data Postingan)
        async function muatPosts() {
            const apiKey = document.getElementById("api_key").value;
            const url = baseUrl + "?endpoint=posts&api_key=" + apiKey;
            
            const response = await fetch(url, { method: "GET" });
            const data = await response.json();
            
            const list = document.getElementById("daftar_posts");
            list.innerHTML = ""; // Bersihkan isi list
            
            for (let i = 0; i < data.length; i++) {
                const post = data[i];
                // Buat baris data beserta tombol Edit dan Hapus
                list.innerHTML += "<li>" + 
                    "[ID: " + post.id + "] " + post.content + 
                    " <button onclick='siapkanEdit(" + post.id + ", \"" + post.content + "\")'>Edit</button>" +
                    " <button onclick='hapusPost(" + post.id + ")'>Hapus</button>" +
                "</li>";
            }
        }

        // 2. CREATE & UPDATE (Simpan Data Baru atau Hasil Edit)
        async function simpanPost() {
            const apiKey = document.getElementById("api_key").value;
            const content = document.getElementById("post_content").value;
            const postId = document.getElementById("post_id").value;
            
            const url = baseUrl + "?endpoint=posts&api_key=" + apiKey;
            
            // Jika postId kosong, berarti kita menambah data baru (POST)
            if (postId == "") {
                const requestData = { content: content };
                await fetch(url, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(requestData)
                });
            } 
            // Jika postId ada isinya, berarti kita merubah data yang sudah ada (PUT)
            else {
                const requestData = { id: postId, content: content };
                await fetch(url, {
                    method: "PUT",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(requestData)
                });
            }
            
            batalEdit(); // Kosongkan text form
            muatPosts(); // Refresh daftar post di layar
        }

        // 3. DELETE (Hapus Data Postingan)
        async function hapusPost(id) {
            const apiKey = document.getElementById("api_key").value;
            const url = baseUrl + "?endpoint=posts&id=" + id + "&api_key=" + apiKey;
            
            await fetch(url, { method: "DELETE" });
            muatPosts(); // Refresh daftar post di layar
        }

        // --- FUNGSI BANTUAN ---

        // Fungsi saat tombol Edit ditekan
        function siapkanEdit(id, content) {
            document.getElementById("post_id").value = id;
            document.getElementById("post_content").value = content;
        }

        // Fungsi saat batal edit atau selesai simpan
        function batalEdit() {
            document.getElementById("post_id").value = "";
            document.getElementById("post_content").value = "";
        }
    </script>
</body>
</html>
