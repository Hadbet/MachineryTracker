<?php

$currentPage = basename($_SERVER['PHP_SELF']);

session_start();
$usuario = $_SESSION['user_id'];
$nombre = $_SESSION['user_nombre'];
$rol = $_SESSION['user_rol'];

if ($rol === 1 && $currentPage === 'usuario.php'){
    echo "<META HTTP-EQUIV='REFRESH' CONTENT='1; URL=index.html'>";
    session_destroy();
}

?>

<nav class="container mx-auto px-6 py-3 flex justify-between items-center">
    <!-- Logo and Portal Title -->
    <div class="flex items-center">
        <img src="images/logoWhite.png" alt="Logo de Grammer" class="h-10 w-10 mr-3 rounded-full">
        <h1 class="text-xl font-bold">Machinery Tracker</h1>
    </div>

    <!-- Desktop Menu -->
    <div class="hidden md:flex items-center space-x-6">
        <a href="inicio.php" class="<?php echo ($currentPage == 'inicio.php') ? 'active-link' : 'hover:text-blue-300'; ?> transition-colors">Inicio</a>
        <?php
        if ($rol === 2){
            ?>
            <a href="usuario.php" class="<?php echo ($currentPage == 'usuario.php') ? 'active-link' : 'hover:text-blue-300'; ?> transition-colors">Usuarios</a>
        <?php } ?>
        <a href="historico.php" class="<?php echo ($currentPage == 'historico.php') ? 'active-link' : 'hover:text-blue-300'; ?> transition-colors">Estaciones</a>
        <a href="perfil.php" class="<?php echo ($currentPage == 'perfil.php') ? 'active-link' : 'hover:text-blue-300'; ?> transition-colors">Perfil</a>
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
    <a href="inicio.php" class="block py-2 <?php echo ($currentPage == 'inicio.php') ? 'text-blue-300 font-bold' : 'hover:text-blue-300'; ?>">Inicio</a>
    <?php
    if ($rol === 2){
        ?>
        <a href="usuario.php" class="block py-2 <?php echo ($currentPage == 'usuario.php') ? 'text-blue-300 font-bold' : 'hover:text-blue-300'; ?>">Usuarios</a>
        <?php
    }
    ?>
    <a href="historico.php" class="block py-2 <?php echo ($currentPage == 'historico.php') ? 'text-blue-300 font-bold' : 'hover:text-blue-300'; ?>">Estaciones</a>
    <a href="perfil.php" class="block py-2 <?php echo ($currentPage == 'perfil.php') ? 'text-blue-300 font-bold' : 'hover:text-blue-300'; ?>">Perfil</a>
</div>