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
                <!-- Loaded dynamically -->
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

    <script>
        window.currentImagePickerTarget = null;

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
            const targetEl = document.getElementById(window.currentImagePickerTarget.id);
            if (targetEl) {
                targetEl.value = url;
                // trigger change event just in case
                targetEl.dispatchEvent(new Event('input', { bubbles: true }));
                targetEl.dispatchEvent(new Event('change', { bubbles: true }));
            }
            if (window.currentImagePickerTarget.cb && typeof window[window.currentImagePickerTarget.cb] === 'function') {
                window[window.currentImagePickerTarget.cb](url);
            }
            closeImagePicker();
        }

        window.onclick = function(event) {
            const modal = document.getElementById('global-img-modal');
            if (event.target === modal) {
                closeImagePicker();
            }
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
