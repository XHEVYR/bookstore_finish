function addToCart(bookId, quantity) {
    // Menggunakan AJAX untuk menambahkan buku ke keranjang
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "cart.php?add_to_cart=" + bookId + "&quantity=" + quantity, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert("Buku telah dimasukkan di keranjang");
            location.reload();
        }
    };
    xhr.send();
}
