<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Profile - Glow-in</title>
  <link rel="stylesheet" href="../assets/CSS/base.css?v=115" />
  <link rel="stylesheet" href="../assets/CSS/profile.css?v=116" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>
<input type="checkbox" id="menu-toggle" class="hidden-checkbox">

<div class="layout">
<?php require_once '../includes/sidebar.php'; ?>

  <main class="main-content">
    <header class="top-header">
      <label for="menu-toggle" class="menu-toggle-btn">
        <i class="fa-solid fa-bars"></i>
      </label>
      <h1>Edit Profile</h1>
    </header>

    <div class="container">
      <div id="profile-loading" class="loading-state my-20">Memuat data profil via REST API...</div>

      <form id="edit-profile-form" class="settings-mockup-card d-none">
        <!-- Top Profile Header & Cover Banner (Clickable) -->
        <div style="position: relative; margin: -28px -32px 24px -32px; border-bottom: 1.5px solid #F3F4F6; border-radius: 32px 32px 0 0; overflow: hidden;">
          <!-- Cover Banner -->
          <div id="header-cover-banner" onclick="openImagePicker('profile-header', 'onHeaderPicSelected')" style="height: 160px; width: 100%; background-color: #E5E7EB; background-size: cover; background-position: center; cursor: pointer; position: relative;">
            <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.25); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.2s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0">
              <span style="color: white; font-weight: 700; background: rgba(0,0,0,0.65); padding: 8px 16px; border-radius: 20px; font-size: 13px;"><i class="fa-regular fa-image"></i> Klik untuk ubah header</span>
            </div>
          </div>

          <!-- Avatar & User Info Row -->
          <div style="padding: 0 32px 20px 32px; display: flex; align-items: flex-end; gap: 18px; margin-top: -44px; position: relative; z-index: 2; background: linear-gradient(to top, rgba(255,255,255,1) 30%, rgba(255,255,255,0));">
            <!-- Clickable Avatar Circle -->
            <div onclick="openImagePicker('profile-pic', 'onProfilePicSelected')" style="position: relative; cursor: pointer; width: 88px; height: 88px; border-radius: 50%; border: 4px solid #FFFFFF; background: #FFFFFF; box-shadow: 0 6px 20px rgba(0,0,0,0.15); flex-shrink: 0;">
              <img src="https://ui-avatars.com/api/?name=User" id="header-avatar" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; display: block;" alt="Avatar">
              <div style="position: absolute; inset: 0; border-radius: 50%; background: rgba(0,0,0,0.45); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.2s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0">
                <i class="fa-solid fa-camera" style="color: white; font-size: 22px;"></i>
              </div>
            </div>

            <div class="settings-user-meta" style="margin-bottom: 4px;">
              <h2 id="header-display-name" style="font-size: 20px; font-weight: 800; margin: 0 0 2px 0; color: #111827;">Loading...</h2>
              <span id="header-username" style="font-size: 14px; color: #6B7280; font-weight: 600;">@user</span>
            </div>
          </div>
        </div>

        <!-- Section 1: Informasi Pribadi dan Informasi -->
        <div class="settings-section">
          <h3 class="settings-section-title">Informasi Pribadi dan Informasi</h3>
          
          <div class="settings-item">
            <div class="settings-item-left">
              <i class="fa-regular fa-user settings-icon"></i>
              <span>Nama Pengguna</span>
            </div>
            <input type="text" id="profile-name" class="settings-input" placeholder="Nama Tampilan">
          </div>

          <div class="settings-item settings-item-textarea">
            <div class="settings-item-left">
              <i class="fa-regular fa-pen-to-square settings-icon"></i>
              <span>Bio</span>
            </div>
            <textarea id="profile-bio" class="settings-textarea" rows="2" placeholder="Tulis bio singkat"></textarea>
          </div>

          <div class="settings-item clickable-setting" onclick="alert('Fitur Ubah Kata Sandi segera hadir!')">
            <div class="settings-item-left">
              <i class="fa-solid fa-lock settings-icon"></i>
              <span>Ubah Kata Sandi</span>
            </div>
            <i class="fa-solid fa-chevron-right settings-arrow"></i>
          </div>

          <div class="settings-item">
            <div class="settings-item-left">
              <i class="fa-solid fa-shield-halved settings-icon"></i>
              <span>Mode Anonim</span>
            </div>
            <label class="ios-switch">
              <input type="checkbox" id="profile-anonim" onchange="toggleAnonimPreview(this)">
              <span class="ios-slider"></span>
            </label>
          </div>
        </div>

        <input type="hidden" id="profile-pos" value="50% 50%">
        <input type="hidden" id="header-pos" value="50% 50%">

        <!-- Section 2: Pengaturan Keamanan dan Akun -->
        <div class="settings-section">
          <h3 class="settings-section-title">Pengaturan Keamanan dan Akun</h3>
          
          <div class="settings-item">
            <div class="settings-item-left">
              <i class="fa-solid fa-gear settings-icon"></i>
              <span>Autentikasi Dua Faktor</span>
            </div>
            <label class="ios-switch">
              <input type="checkbox" onclick="alert('Autentikasi 2FA aktif')">
              <span class="ios-slider"></span>
            </label>
          </div>

          <div class="settings-item clickable-setting" onclick="alert('Kelola Perangkat segera hadir!')">
            <div class="settings-item-left">
              <i class="fa-solid fa-wave-square settings-icon"></i>
              <span>Kelola Perangkat</span>
            </div>
            <i class="fa-solid fa-chevron-right settings-arrow"></i>
          </div>
        </div>

        <!-- Section 3: Lainnya -->
        <div class="settings-section no-border">
          <h3 class="settings-section-title">Lainnya</h3>

          <div class="settings-item clickable-setting" onclick="alert('Riwayat laporan kosong')">
            <div class="settings-item-left">
              <i class="fa-regular fa-file-lines settings-icon"></i>
              <span>Riwayat Laporan Saya</span>
            </div>
            <i class="fa-solid fa-chevron-right settings-arrow"></i>
          </div>

          <div class="settings-item clickable-setting" onclick="alert('Status Responder: Aktif')">
            <div class="settings-item-left">
              <i class="fa-regular fa-circle-user settings-icon"></i>
              <span>Status Responder</span>
            </div>
            <i class="fa-solid fa-chevron-right settings-arrow"></i>
          </div>

          <div class="settings-item clickable-setting" onclick="alert('Tidak ada akun terblokir')">
            <div class="settings-item-left">
              <i class="fa-solid fa-ban settings-icon"></i>
              <span>Akun Terblokir</span>
            </div>
            <i class="fa-solid fa-chevron-right settings-arrow"></i>
          </div>
        </div>

        <!-- Hidden inputs untuk menyimpan data pendukung agar tidak terhapus -->
        <input type="hidden" id="profile-location" value="">
        <input type="hidden" id="profile-pic" value="">
        <input type="hidden" id="profile-header" value="">

        <!-- Actions -->
        <div class="settings-footer">
          <button type="button" class="btn btn-primary settings-save-btn submit" onclick="saveProfile()">Simpan Perubahan</button>
        </div>
      </form>

<script>
    const apiKey = "<?= $_SESSION['api_key'] ?? '' ?>";
    const currentUserId = <?= $_SESSION['user_id'] ?? 0 ?>;
    let currentUserData = {};

    document.addEventListener('DOMContentLoaded', () => {
        fetch('../controllers/userController.php?id=' + currentUserId, {
            headers: { 'Authorization': 'Bearer ' + apiKey }
        })
        .then(res => res.json())
        .then(user => {
            document.getElementById('profile-loading').classList.add('d-none');
            document.getElementById('edit-profile-form').classList.remove('d-none');

            if(user && !user.error) {
                currentUserData = user;
                const displayName = user.name || user.username || 'User';
                const isAnon = parseInt(user.is_anonymous || 0) === 1;

                document.getElementById('profile-name').value = user.name || '';
                document.getElementById('profile-bio').value = user.bio || '';
                document.getElementById('profile-location').value = user.location || '';
                document.getElementById('profile-pic').value = user.profile_pic || '';
                document.getElementById('profile-header').value = user.header_pic || '';
                document.getElementById('profile-pos').value = user.profile_pos || '50% 50%';
                document.getElementById('header-pos').value = user.header_pos || '50% 50%';
                document.getElementById('profile-anonim').checked = isAnon;

                // Apply saved positions
                if (user.profile_pos) document.getElementById('header-avatar').style.objectPosition = user.profile_pos;
                if (user.header_pos) document.getElementById('header-cover-banner').style.backgroundPosition = user.header_pos;

                // Update Top Preview
                updateHeaderPreview(displayName, user.username, user.profile_pic, user.header_pic, isAnon);
            }
        })
        .catch(() => {
            document.getElementById('profile-loading').textContent = 'Gagal memuat profil via REST API.';
        });

        // Event listener saat mengetik nama
        document.getElementById('profile-name').addEventListener('input', (e) => {
            const isAnon = document.getElementById('profile-anonim').checked;
            updateHeaderPreview(e.target.value.trim() || currentUserData.username || 'User', currentUserData.username, currentUserData.profile_pic, currentUserData.header_pic, isAnon);
        });
    });

    function updateHeaderPreview(name, handle, pic, cover, isAnon) {
        const avatarEl = document.getElementById('header-avatar');
        const bannerEl = document.getElementById('header-cover-banner');
        const nameEl = document.getElementById('header-display-name');
        const handleEl = document.getElementById('header-username');

        if (cover) {
            bannerEl.style.backgroundImage = `url('${cover}')`;
        }

        if (isAnon) {
            avatarEl.src = 'https://ui-avatars.com/api/?name=Anonim&background=4b5563&color=ffffff';
            nameEl.textContent = 'Mode Anonim';
            handleEl.textContent = '@anonim';
        } else {
            avatarEl.src = pic || ('https://ui-avatars.com/api/?name=' + urlencode(name) + '&background=ff6b00&color=ffffff');
            nameEl.textContent = name;
            handleEl.textContent = '@' + (handle || 'user');
        }
    }

    function onProfilePicSelected(url) {
        if(url) {
            document.getElementById('profile-pic').value = url;
            if(!document.getElementById('profile-anonim').checked) {
                document.getElementById('header-avatar').src = url;
            }
        }
    }

    function onHeaderPicSelected(url) {
        if(url) {
            document.getElementById('profile-header').value = url;
            document.getElementById('header-cover-banner').style.backgroundImage = `url('${url}')`;
        }
    }

    function toggleAnonimPreview(checkbox) {
        const currentName = document.getElementById('profile-name').value.trim() || currentUserData.name || currentUserData.username || 'User';
        updateHeaderPreview(currentName, currentUserData.username, currentUserData.profile_pic, currentUserData.header_pic, checkbox.checked);
    }

    function saveProfile() {
        const formEl = document.getElementById('edit-profile-form');
        if (formEl && !formEl.checkValidity()) {
            formEl.reportValidity();
            return;
        }

        const btnEl = document.querySelector('.settings-save-btn');
        const origText = btnEl ? btnEl.textContent : 'Simpan Perubahan';
        if (btnEl) {
            btnEl.disabled = true;
            btnEl.textContent = 'Menyimpan...';
        }

        const payload = {
            name: document.getElementById('profile-name').value.trim(),
            bio: document.getElementById('profile-bio').value.trim(),
            location: document.getElementById('profile-location').value.trim(),
            profile_pic: document.getElementById('profile-pic').value.trim(),
            header_pic: document.getElementById('profile-header').value.trim(),
            profile_pos: document.getElementById('profile-pos').value.trim(),
            header_pos: document.getElementById('header-pos').value.trim(),
            is_anonymous: document.getElementById('profile-anonim').checked ? 1 : 0
        };

        fetch('../controllers/userController.php?id=' + currentUserId, {
            method: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + apiKey,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (btnEl) {
                btnEl.disabled = false;
                btnEl.textContent = origText;
            }
            if(data.success) {
                alert("Pengaturan profil berhasil diperbarui!");
                window.location.href = 'profile.php';
            } else {
                alert("Error: " + (data.error || 'Gagal menyimpan'));
            }
        })
        .catch(err => {
            if (btnEl) {
                btnEl.disabled = false;
                btnEl.textContent = origText;
            }
            alert("Gagal menyimpan profil: " + err);
        });
    }
</script>
    </div>
  </main>

<?php require_once '../includes/rightbar.php'; ?>
<?php require_once '../includes/footer.php'; ?>
</div>
</body>
</html>
