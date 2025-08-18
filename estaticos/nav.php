<nav class="container mx-auto px-6 py-3 flex justify-between items-center">
    <!-- Logo and Portal Title -->
    <div class="flex items-center">
        <img src="images/logoWhite.png" alt="Logo de Grammer" class="h-10 w-10 mr-3 rounded-full">
        <h1 class="text-xl font-bold">Machinery Tracker</h1>
    </div>

    <!-- Desktop Menu -->
    <div class="hidden md:flex items-center space-x-6">
        <a href="inicio.php" class="hover:text-blue-300 transition-colors">Inicio</a>
        <a href="usuario.php" class="hover:text-blue-300 transition-colors">Usuarios</a>
        <a href="historico.php" class="hover:text-blue-300 transition-colors">Estaciones</a>
        <a href="perfil.php" class="hover:text-blue-300 transition-colors">Perfil</a>
    </div>

    <!-- Mobile Menu Button -->
    <div class="md:hidden">
        <button id="mobile-menu-button" class="text-white focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>
    </div>
</nav>
<!-- Mobile Menu -->
<div id="mobile-menu" class="hidden md:hidden px-6 pb-4">
    <a href="#" class="block py-2 hover:text-blue-300">Inicio</a>
    <a href="#" class="block py-2 hover:text-blue-300">Usuarios</a>
    <a href="#" class="block py-2 hover:text-blue-300">Estaciones</a>
    <a href="#" class="block py-2 hover:text-blue-300">Perfil</a>
</div>