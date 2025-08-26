const mobileMenuButton = document.getElementById('mobile-menu-button');
const mobileMenu = document.getElementById('mobile-menu');
mobileMenuButton.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
});

// --- Image Preview Functionality ---
function setupImagePreview(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    input.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.innerHTML = `<img src="${e.target.result}" alt="Vista previa">`;
            };
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = '<span>Vista Previa</span>';
        }
    });
}
setupImagePreview('imagen_estacion', 'preview_imagen_estacion');
setupImagePreview('evidencia_salida', 'preview_evidencia_salida');
setupImagePreview('evidencia_entrada', 'preview_evidencia_entrada');
setupImagePreview('evidencia_resguardo', 'preview_evidencia_resguardo');

// --- NEW: Dynamic Form Fields based on Disposition ---
const disposicionSelect = document.getElementById('disposicion');
const containerSalida = document.getElementById('container_evidencia_salida');
const containerEntrada = document.getElementById('container_evidencia_entrada');
const containerResguardo = document.getElementById('container_evidencia_resguardo');
const inputSalida = document.getElementById('evidencia_salida');
const inputEntrada = document.getElementById('evidencia_entrada');
const inputResguardo = document.getElementById('evidencia_resguardo');

function handleDisposicionChange() {
    const value = disposicionSelect.value;

    // Reset all to hidden and not required by default
    [containerSalida, containerEntrada, containerResguardo].forEach(c => c.style.display = 'none');
    [inputSalida, inputEntrada, inputResguardo].forEach(i => i.required = false);

    switch (value) {
        case 'en_uso':
            // No evidence needed
            break;
        case 'resguardo':
            // All evidence needed
            [containerSalida, containerEntrada, containerResguardo].forEach(c => c.style.display = 'block');
            [inputSalida, inputResguardo].forEach(i => i.required = true); // Entrada is optional
            break;
        case 'scrap':
            // Only exit evidence
            containerSalida.style.display = 'block';
            inputSalida.required = true;
            break;
        case 'modificacion':
            // All evidence needed
            [containerSalida, containerEntrada, containerResguardo].forEach(c => c.style.display = 'block');
            [inputSalida, inputResguardo].forEach(i => i.required = true); // Entrada is optional
            break;
    }
}
disposicionSelect.addEventListener('change', handleDisposicionChange);
// Initial call to set the correct state on page load
handleDisposicionChange();


// --- Image Resizing Functionality ---
function resizeImage(file, options = { maxWidth: 1024, maxHeight: 1024, quality: 0.7 }) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = (event) => {
            const img = new Image();
            img.src = event.target.result;
            img.onload = () => {
                const canvas = document.createElement('canvas');
                let { width, height } = img;
                if (width > height) {
                    if (width > options.maxWidth) {
                        height *= options.maxWidth / width;
                        width = options.maxWidth;
                    }
                } else {
                    if (height > options.maxHeight) {
                        width *= options.maxHeight / height;
                        height = options.maxHeight;
                    }
                }
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);
                canvas.toBlob((blob) => { resolve(blob); }, file.type, options.quality);
            };
            img.onerror = reject;
        };
        reader.onerror = reject;
    });
}

// --- Form Submission with Fetch and Resizing ---
const form = document.getElementById('stationForm');
const submitButton = document.getElementById('submit-button');
const formMessage = document.getElementById('form-message');

form.addEventListener('submit', async (event) => {
    event.preventDefault();

    submitButton.disabled = true;
    submitButton.textContent = 'Processing images...';
    formMessage.textContent = '';

    const formData = new FormData();
    // Append all text/select fields
    for (const pair of new FormData(form).entries()) {
        if (typeof pair[1] === 'string') {
            formData.append(pair[0], pair[1]);
        }
    }

    try {
        // Handle image resizing based on visibility and requirement
        const imageInputs = [
            document.getElementById('imagen_estacion'),
            document.getElementById('evidencia_salida'),
            document.getElementById('evidencia_entrada'),
            document.getElementById('evidencia_resguardo')
        ];

        const resizePromises = imageInputs.map(async (input) => {
            // Only process visible and selected files
            if (input.offsetParent !== null && input.files.length > 0) {
                const resizedBlob = await resizeImage(input.files[0]);
                formData.append(input.name, resizedBlob, input.files[0].name);
            }
        });

        await Promise.all(resizePromises);

        submitButton.textContent = 'Sending...';

        const url = 'https://grammermx.com/Ingenieria/MachineryTracker/dao/daoRegistroMaquinaria.php'; // Your PHP script URL
        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error(`Error del servidor: ${response.statusText}`);
        }

        const result = await response.json();

        if (result.status === 'success') {

            Swal.fire({
                title: "Registered!",
                icon: "success",
                draggable: true
            });

            formMessage.innerHTML = `<p class="text-green-600 font-semibold">${result.message}</p>`;
            form.reset();
            // Clear previews and reset form state
            ['preview_imagen_estacion', 'preview_evidencia_salida', 'preview_evidencia_entrada', 'preview_evidencia_resguardo'].forEach(id => {
                document.getElementById(id).innerHTML = '<span>Vista Previa</span>';
            });
            handleDisposicionChange();
        } else {
            throw new Error(result.message || 'Ocurrió un error desconocido.');
        }

    } catch (error) {
        formMessage.innerHTML = `<p class="text-red-600 font-semibold">Error: ${error.message}</p>`;
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = 'Registrar Estación';
    }
});