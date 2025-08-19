const changePasswordForm = document.getElementById('changePasswordForm');
const submitButton = document.getElementById('submit-button');
const formMessage = document.getElementById('form-message');

changePasswordForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    // Client-side validation
    if (newPassword !== confirmPassword) {
        formMessage.innerHTML = '<p class="text-red-600">Las nuevas contraseñas no coinciden.</p>';
        return;
    }

    formMessage.textContent = '';
    submitButton.disabled = true;
    submitButton.textContent = 'Actualizando...';

    const formData = new FormData(changePasswordForm);
    // Replace with the URL of your PHP script for updating the password
    const url = 'https://grammermx.com/Ingenieria/MachineryTracker/dao/daoActualizarContrasena.php';

    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            formMessage.innerHTML = `<p class="text-green-600">${result.message}</p>`;
            changePasswordForm.reset();
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        formMessage.innerHTML = `<p class="text-red-600">Error: ${error.message}</p>`;
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = 'Actualizar Contraseña';
    }
});