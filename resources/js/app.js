
import './bootstrap';

document.addEventListener('DOMContentLoaded', function () {

    // Get password input and toggle button
    const password = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    // Check if both elements exist
    if (password && togglePassword) {

        // Toggle password visibility when clicking the button
        togglePassword.addEventListener('click', function () {

            const isPassword = password.type === 'password';

            // Show or hide password
            password.type = isPassword ? 'text' : 'password';

            // Change button icon
            togglePassword.textContent = isPassword ? '🙈' : '👁';

            // Update accessibility label
            togglePassword.setAttribute(
                'aria-label',
                isPassword ? 'Hide password' : 'Show password'
            );

        });
    }

});
