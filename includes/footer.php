    </div> <!-- end layout -->

    <label for="menu-toggle" id="overlay"></label>

    <!-- Global Popup Modal: Pilih Gambar -->
    <div id="global-img-modal" class="modal-overlay d-none" style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 16px;">
        <div style="background: #FFFFFF; border-radius: 24px; max-width: 520px; width: 100%; padding: 24px; box-shadow: 0 20px 50px rgba(0,0,0,0.2); position: relative; max-height: 85vh; display: flex; flex-direction: column;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #EEEEEE; padding-bottom: 12px;">
                <h3 style="margin: 0; font-size: 18px; font-weight: 800; color: #111827;">🖼️ Pilih Gambar</h3>
                <button type="button" onclick="closeImagePicker()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6B7280;">&times;</button>
            </div>
            
            <p style="margin: 0 0 12px 0; font-size: 13.5px; color: #6B7280;">Klik salah satu foto di dalam folder <code>IMG</code>:</p>
            
            <div id="global-img-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 12px; overflow-y: auto; flex: 1; padding: 4px;">
                <div style="text-align: center; color: #9CA3AF; grid-column: 1/-1; padding: 20px;">Memuat gambar...</div>
            </div>

            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #EEEEEE;">
                <label style="display: block; font-size: 12.5px; font-weight: 600; color: #374151; margin-bottom: 6px;">Atau masukkan URL eksternal (https://...)</label>
                <div style="display: flex; gap: 8px;">
                    <input type="text" id="global-custom-url" placeholder="https://images.unsplash.com/..." style="flex: 1; border: 1.5px solid #E5E7EB; border-radius: 10px; padding: 8px 12px; font-size: 13.5px; outline: none;">
                    <button type="button" onclick="confirmCustomImageUrl()" style="background: #FF6B00; color: white; border: none; border-radius: 10px; padding: 8px 16px; font-weight: 700; cursor: pointer;">Pilih</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Popup Modal: Sesuaikan Posisi (Crop & Drag) -->
    <div id="global-pos-modal" class="modal-overlay d-none" style="position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 10000; display: flex; align-items: center; justify-content: center; padding: 16px;">
        <div style="background: #FFFFFF; border-radius: 24px; max-width: 480px; width: 100%; padding: 24px; box-shadow: 0 25px 60px rgba(0,0,0,0.3); text-align: center; display: flex; flex-direction: column; align-items: center;">
            <h3 style="margin: 0 0 8px 0; font-size: 18px; font-weight: 800; color: #111827;">✥ Geser untuk Menyesuaikan</h3>
            <p style="margin: 0 0 16px 0; font-size: 13px; color: #6B7280;">Klik & tahan lalu geser gambar di dalam bingkai agar pas:</p>
            
            <div id="pos-modal-frame" style="width: 100%; height: 200px; background-color: #E5E7EB; background-repeat: no-repeat; background-size: cover; background-position: 50% 50%; cursor: grab; position: relative; overflow: hidden; border: 3px solid #FF6B00; box-shadow: inset 0 0 20px rgba(0,0,0,0.2);">
            </div>

            <div style="display: flex; gap: 12px; width: 100%; margin-top: 20px;">
                <button type="button" onclick="closePosModal()" style="flex: 1; background: #F3F4F6; color: #374151; border: none; border-radius: 12px; padding: 12px; font-weight: 700; cursor: pointer;">Batal</button>
                <button type="button" onclick="confirmReposition()" style="flex: 2; background: #FF6B00; color: white; border: none; border-radius: 12px; padding: 12px; font-weight: 800; cursor: pointer; box-shadow: 0 4px 14px rgba(255,107,0,0.3);">Simpan Posisi</button>
            </div>
        </div>
    </div>

    <script>
        window.currentImagePickerTarget = null;
        let tempPickedUrl = '';
        let posDragState = { active: false, startX: 0, startY: 0, posX: 50, posY: 50 };

        function openImagePicker(targetInputId, callbackFuncName) {
            window.currentImagePickerTarget = { id: targetInputId, cb: callbackFuncName };
            const modal = document.getElementById('global-img-modal');
            modal.classList.remove('d-none');
            modal.style.display = 'flex';

            fetch('../controllers/imageController.php')
            .then(res => res.json())
            .then(images => {
                const grid = document.getElementById('global-img-grid');
                grid.innerHTML = '';
                if (!Array.isArray(images) || images.length === 0) {
                    grid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; color: #6B7280;">Tidak ada gambar di folder IMG.</div>';
                    return;
                }
                images.forEach(imgUrl => {
                    const cleanName = imgUrl.split('/').pop();
                    const item = document.createElement('div');
                    item.style.cssText = 'border: 2px solid #F3F4F6; border-radius: 12px; overflow: hidden; cursor: pointer; transition: all 0.2s; background: #F9FAFB; text-align: center;';
                    item.onclick = () => selectImageFromPicker(imgUrl);
                    item.onmouseover = () => item.style.borderColor = '#FF6B00';
                    item.onmouseout = () => item.style.borderColor = '#F3F4F6';
                    item.innerHTML = `
                        <img src="${imgUrl}" style="width: 100%; height: 90px; object-fit: cover; display: block;" alt="${cleanName}">
                        <div style="padding: 6px 4px; font-size: 11.5px; font-weight: 600; color: #374151; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${cleanName}</div>
                    `;
                    grid.appendChild(item);
                });
            })
            .catch(() => {
                document.getElementById('global-img-grid').innerHTML = '<div style="grid-column: 1/-1; color: red;">Gagal memuat daftar gambar.</div>';
            });
        }

        function closeImagePicker() {
            const modal = document.getElementById('global-img-modal');
            modal.classList.add('d-none');
            modal.style.display = 'none';
        }

        function confirmCustomImageUrl() {
            const val = document.getElementById('global-custom-url').value.trim();
            if (val) selectImageFromPicker(val);
        }

        function selectImageFromPicker(url) {
            if (!url || !window.currentImagePickerTarget) return;
            closeImagePicker();

            // Jika yang diganti adalah foto profil atau header, buka modal drag posisi!
            if (window.currentImagePickerTarget.id === 'profile-pic' || window.currentImagePickerTarget.id === 'profile-header') {
                tempPickedUrl = url;
                openPosModal(window.currentImagePickerTarget.id, url);
                return;
            }

            // Jika postingan biasa
            const targetEl = document.getElementById(window.currentImagePickerTarget.id);
            if (targetEl) {
                targetEl.value = url;
                targetEl.dispatchEvent(new Event('input', { bubbles: true }));
            }
            if (window.currentImagePickerTarget.cb && typeof window[window.currentImagePickerTarget.cb] === 'function') {
                window[window.currentImagePickerTarget.cb](url);
            }
        }

        // --- Positioning Modal Drag Helpers ---
        function openPosModal(type, url) {
            const modal = document.getElementById('global-pos-modal');
            const frame = document.getElementById('pos-modal-frame');
            
            if (type === 'profile-pic') {
                frame.style.width = '180px';
                frame.style.height = '180px';
                frame.style.borderRadius = '50%';
            } else {
                frame.style.width = '100%';
                frame.style.height = '160px';
                frame.style.borderRadius = '16px';
            }

            frame.style.backgroundImage = `url('${url}')`;
            posDragState.posX = 50;
            posDragState.posY = 50;
            frame.style.backgroundPosition = '50% 50%';

            modal.classList.remove('d-none');
            modal.style.display = 'flex';
        }

        function closePosModal() {
            const modal = document.getElementById('global-pos-modal');
            modal.classList.add('d-none');
            modal.style.display = 'none';
        }

        function confirmReposition() {
            const posStr = `${Math.round(posDragState.posX)}% ${Math.round(posDragState.posY)}%`;
            const inpId = window.currentImagePickerTarget.id;
            const targetEl = document.getElementById(inpId);
            if (targetEl) targetEl.value = tempPickedUrl;

            if (inpId === 'profile-pic') {
                const posEl = document.getElementById('profile-pos');
                if (posEl) posEl.value = posStr;
                const av = document.getElementById('header-avatar');
                if (av) { av.src = tempPickedUrl; av.style.objectPosition = posStr; }
            } else if (inpId === 'profile-header') {
                const posEl = document.getElementById('header-pos');
                if (posEl) posEl.value = posStr;
                const ban = document.getElementById('header-cover-banner');
                if (ban) { ban.style.backgroundImage = `url('${tempPickedUrl}')`; ban.style.backgroundPosition = posStr; }
            }

            closePosModal();
        }

        // Drag events setup
        document.addEventListener('DOMContentLoaded', () => {
            const frame = document.getElementById('pos-modal-frame');
            if (!frame) return;

            const startDrag = (clientX, clientY) => {
                posDragState.active = true;
                posDragState.startX = clientX;
                posDragState.startY = clientY;
                frame.style.cursor = 'grabbing';
            };

            const moveDrag = (clientX, clientY) => {
                if (!posDragState.active) return;
                const dx = clientX - posDragState.startX;
                const dy = clientY - posDragState.startY;
                
                posDragState.posX = Math.max(0, Math.min(100, posDragState.posX - (dx * 0.35)));
                posDragState.posY = Math.max(0, Math.min(100, posDragState.posY - (dy * 0.35)));
                
                frame.style.backgroundPosition = `${posDragState.posX}% ${posDragState.posY}%`;
                posDragState.startX = clientX;
                posDragState.startY = clientY;
            };

            const endDrag = () => {
                if (posDragState.active) {
                    posDragState.active = false;
                    frame.style.cursor = 'grab';
                }
            };

            frame.addEventListener('mousedown', e => startDrag(e.clientX, e.clientY));
            window.addEventListener('mousemove', e => moveDrag(e.clientX, e.clientY));
            window.addEventListener('mouseup', endDrag);

            frame.addEventListener('touchstart', e => startDrag(e.touches[0].clientX, e.touches[0].clientY));
            window.addEventListener('touchmove', e => moveDrag(e.touches[0].clientX, e.touches[0].clientY));
            window.addEventListener('touchend', endDrag);
        });

        window.onclick = function(event) {
            const modalImg = document.getElementById('global-img-modal');
            const modalPos = document.getElementById('global-pos-modal');
            if (event.target === modalImg) closeImagePicker();
            if (event.target === modalPos) closePosModal();

            if (!event.target.closest('.post-dropdown-btn')) {
                var dropdowns = document.getElementsByClassName("post-dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
</html>
