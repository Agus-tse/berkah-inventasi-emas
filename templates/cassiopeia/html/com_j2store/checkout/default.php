<?php
defined('_JEXEC') or die('Restricted access');
?>
<h1>Checkout</h1>
<form action="index.php?option=com_j2store&task=checkout" method="post">
    <label for="jumlah_pembelian">Jumlah Pembelian (gram):</label>
    <input type="number" id="jumlah_pembelian" name="jumlah_pembelian" min="1" required>
    <button type="submit">Beli</button>
</form>
