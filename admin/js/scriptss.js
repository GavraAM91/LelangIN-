document.addEventListener('DOMContentLoaded', function() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "ajax_timer.php", true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            // Parse JSON response
            var products = JSON.parse(xhr.responseText);

            // Menyiapkan string HTML untuk menampilkan produk
            var displayHtml = products.map(function(product) {
                return "<p>Nama Produk: " + product.name + ", Harga: " + product.price + "</p>";
            }).join('');

            // Menampilkan data di DOM
            document.getElementById('dataDisplay').innerHTML = displayHtml;
            
        } else {
            console.log('HTTP request failed:', xhr.status, xhr.statusText);
        }
    };
    xhr.onerror = function() {
        console.log('There was an error making the request');
    };
    xhr.send() = function(){
        document.getElementById('dataDisplay').innerHTML = displayHtml;
    };
});
