document.addEventListener("DOMContentLoaded", function () {
    const apiKey = "6b146801fe79866e070eb82cbfecc71a229787041b3194e020fd10efee35ec8d";

    const provEl = document.getElementById("province");
    const cityEl = document.getElementById("city");
    const districtEl = document.getElementById("district");
    const villageEl = document.getElementById("village");
    const postalEl = document.getElementById("postal_code");

    const provCodeInput = document.getElementById("province_code");
    const cityCodeInput = document.getElementById("city_code");
    const districtCodeInput = document.getElementById("district_code");
    const destinationIdInput = document.getElementById("destination_id");

    const dropdownWrapper = document.getElementById("postal_dropdown_wrapper");
    const dropdown = document.getElementById("postal_dropdown");

    if (!provEl || !cityEl || !districtEl || !villageEl || !postalEl) {
        console.warn("Beberapa elemen wilayah tidak ditemukan.");
        return;
    }

    const oldProvince = provCodeInput?.value || null;
    const oldCity = cityCodeInput?.value || null;
    const oldDistrict = districtCodeInput?.value || null;

    const reset = (el, label = "Pilih") => {
        el.innerHTML = `<option value="">${label}</option>`;
        el.disabled = true;
    };

    const populate = (el, items, selectedId = null) => {
        items.forEach(item => {
            const opt = document.createElement("option");
            opt.value = item.id;
            opt.textContent = item.name;
            el.appendChild(opt);
        });
        if (selectedId) el.value = selectedId;
        el.disabled = false;
    };

    // Provinsi
    fetch(`https://api.binderbyte.com/wilayah/provinsi?api_key=${apiKey}`)
        .then(res => res.json())
        .then(data => {
            populate(provEl, data.value, oldProvince);
            if (oldProvince) provEl.dispatchEvent(new Event("change"));
        });

    provEl.addEventListener("change", () => {
        const id = provEl.value;
        provCodeInput.value = id;
        reset(cityEl, "Pilih Kota/Kabupaten");
        reset(districtEl, "Pilih Kecamatan");
        reset(villageEl, "Pilih Kelurahan");
        postalEl.value = "";
        dropdownWrapper?.classList.add("hidden");

        fetch(`https://api.binderbyte.com/wilayah/kabupaten?api_key=${apiKey}&id_provinsi=${id}`)
            .then(res => res.json())
            .then(data => {
                populate(cityEl, data.value, oldCity);
                if (oldCity) cityEl.dispatchEvent(new Event("change"));
            });
    });

    cityEl.addEventListener("change", () => {
        const id = cityEl.value;
        cityCodeInput.value = id;
        reset(districtEl, "Pilih Kecamatan");
        reset(villageEl, "Pilih Kelurahan");
        postalEl.value = "";
        dropdownWrapper?.classList.add("hidden");

        fetch(`https://api.binderbyte.com/wilayah/kecamatan?api_key=${apiKey}&id_kabupaten=${id}`)
            .then(res => res.json())
            .then(data => {
                populate(districtEl, data.value, oldDistrict);
                if (oldDistrict) districtEl.dispatchEvent(new Event("change"));
            });
    });

    districtEl.addEventListener("change", () => {
        const id = districtEl.value;
        districtCodeInput.value = id;
        destinationIdInput.value = "";
        reset(villageEl, "Pilih Kelurahan");
        postalEl.value = "";
        dropdownWrapper?.classList.add("hidden");

        fetch(`https://api.binderbyte.com/wilayah/kelurahan?api_key=${apiKey}&id_kecamatan=${id}`)
            .then(res => res.json())
            .then(data => {
                populate(villageEl, data.value);
            });
    });

    villageEl.addEventListener("change", () => {
        const kelurahan = villageEl.options[villageEl.selectedIndex]?.text;
        const kecamatan = districtEl.options[districtEl.selectedIndex]?.text;
        const kota = cityEl.options[cityEl.selectedIndex]?.text;

        if (!kelurahan || !kecamatan || !kota) return;

        const keyword = `${kelurahan}, ${kecamatan}`;
        console.log("🔍 Keyword dikirim ke Komerce:", keyword);

        fetch(`/get-komerce-postal?village=${encodeURIComponent(keyword)}`)
            .then(res => res.json())
            .then(response => {
                const komerceData = response.data ?? response;
                console.log("📦 Komerce Data:", komerceData); // Debug

                dropdown.innerHTML = "";
                dropdownWrapper.classList.add("hidden");

                if (komerceData.length > 1) {
                    komerceData.forEach(item => {
                        const opt = document.createElement("option");
                        opt.value = item.zip_code;
                        opt.textContent = `${item.zip_code} - ${item.label}`;
                        opt.dataset.destinationId = item.id;
                        dropdown.appendChild(opt);
                    });

                    dropdownWrapper.classList.remove("hidden");
                    postalEl.value = komerceData[0].zip_code;
                    destinationIdInput.value = komerceData[0].id;

                    dropdown.addEventListener("change", () => {
                        postalEl.value = dropdown.value;
                        destinationIdInput.value = dropdown.options[dropdown.selectedIndex].dataset.destinationId;
                    });
                } else if (komerceData.length === 1) {
                    if (komerceData[0].zip_code) {
                        postalEl.value = komerceData[0].zip_code;
                        destinationIdInput.value = komerceData[0].id;
                    } else {
                        postalEl.value = "Tidak ditemukan";
                        destinationIdInput.value = "";
                    }
                } else {
                    postalEl.value = "Tidak ditemukan";
                    destinationIdInput.value = "";
                }
            })
            .catch(err => {
                console.error("❌ Gagal mengambil postal dari Komerce:", err);
                postalEl.value = "Gagal ambil";
                destinationIdInput.value = "";
            });
    });
});
