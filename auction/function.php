<?php

class IDGenerator
{
    private $lastID;

    public function __construct($lastID = 0)
    {
        $this->lastID = $lastID;
    }

    public function generateID()
    {
        $this->lastID++;
        return "ADR" . str_pad($this->lastID, 3, "0", STR_PAD_LEFT);
    }
}

class IDGeneratorcart
{
    private $lastID;

    public function __construct($lastID = 0)
    {
        $this->lastID = $lastID;
    }

    public function generateID()
    {
        $this->lastID++;
        return "CART" . str_pad($this->lastID, 3, "0", STR_PAD_LEFT);
    }
}

class database
{
    private $host = "localhost"; // Host database
    private $db_name = "db_lelang"; // Nama database
    private $username = "root"; // Username database
    private $password = ""; // Password database
    private $conn;

    // Method untuk membuat koneksi ke database
    public function getConnection()
    {
        $this->conn = null; // Inisialisasi koneksi sebagai null

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            // Set charset koneksi ke utf8
            $this->conn->set_charset("utf8");
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        return $this->conn; // Mengembalikan objek koneksi
    }
    public function getLastAddressID()
    {
        $this->conn = $this->getConnection();
        $result = $this->conn->query("SELECT id_address FROM tb_address ORDER BY id_address DESC LIMIT 1");
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastID = $row['id_address'];
            return intval(substr($lastID, 3)); // Mengembalikan hanya bagian numerik dari ID
        } else {
            return 0; // Jika tidak ada entri, mulai dari 0
        }
    }

    public function getLastCartID()
    {
        $this->conn = $this->getConnection();
        $result = $this->conn->query("SELECT id_cart FROM tb_cart ORDER BY id_cart DESC LIMIT 1");
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastID = $row['id_cart'];
            return intval(substr($lastID, 3)); // Mengembalikan hanya bagian numerik dari ID
        } else {
            return 0; // Jika tidak ada entri, mulai dari 0
        }
    }
    
}

class address
{
    private $id_user, $desa, $kecamatan, $kota, $provinsi, $negara;

    public function __construct($id_user = "id_user", $desa = "desa", $kecamatan = "kecamatan", $kota = "kota", $provinsi = "provinsi", $negara = "negara")
    {
        $this->id_user = $id_user;
        $this->desa = $desa;
        $this->kecamatan = $kecamatan;
        $this->kota = $kota;
        $this->provinsi = $provinsi;
        $this->negara = $negara;
    }

    public function addAddress()
    {
        //open database
        $db = new database();
        $lastIDFromDB = $db->getLastAddressID(); // Dapatkan lastID dari database
        $generator = new IDGenerator($lastIDFromDB); // Inisialisasi IDGenerator dengan lastID
        $newID = $generator->generateID(); // Generate ID baru

        //insert into query
        $query_data = "INSERT INTO `tb_address` (`id_address`,`id_user`, `desa`, `kecamatan`, `kota/kabupaten`, `provinsi`, `negara`) VALUES (?,?,?,?,?,?,?)";
        $query_data = $db->getConnection()->prepare($query_data);
        $query_data->bind_param("sssssss",$newIDCart, $newID, $this->id_user, $this->desa, $this->kecamatan, $this->kota, $this->provinsi, $this->negara);

        if ($query_data->execute()) {
            echo "<script>
                alert('data berhasil diinput ');
            </script>";
            header("Location: ../index.php");
        } else {
            echo "<script>
                alert('data gagal diinput ');
            </script>";
            header("Location: ../index.php");
            return false;
        }
    }

    public function editAddress()
    {
        $db = new database();

        //insert into query
        $query_data = "UPDATE `tb_address` SET `desa`=?,`kecamatan`=?,`kota/kabupaten`=?,`provinsi`=?,`negara`=? WHERE id_user = ?";
        $query_data = $db->getConnection()->prepare($query_data);
        $query_data->bind_param("ssssss", $this->desa, $this->kecamatan, $this->kota, $this->provinsi, $this->negara, $this->id_user);

        if ($query_data->execute()) {
            echo "<script>
            alert('data berhasil di update ');
            </script>";
        } else {
            echo "<script>
            alert('data gagal di update ');
        </script>";
            return false;
        }
    }
}

class checkout
{
    private $product_id, $user_id, $address_id;

    public function __construct($product_id = "product_id", $user_id = "user_id", $address_id)
    {
        $this->product_id = $product_id;
        $this->user_id = $user_id;
        $this->address_id = $address_id;
    }

    //FUNCTION RANDOM CODE 
    public function generateRandomCode()
    {
        $prefix = 'GAMS'; // You can customize the prefix
        $randomCode = $prefix . uniqid() . mt_rand(1000, 9999);

        return $randomCode;
    }

    public function buyProduct()
    {
        //set time
        date_default_timezone_set("Asia/Jakarta");
        $date = ("Y-m-d H-i-s");

        //mengambil data dari generateRandomCode()
        $random_code = $this->generateRandomCode();

        //define database
        $db = new database();

        //open tb_cart
        $query_cart = "SELECT * FROM tb_cart WHERE id_user = ?";
        $query_cart = $db->getConnection()->prepare($query_cart);
        $query_cart->bind_param("s", $this->user_id);
        $query_cart->execute();

        //query insert into tb_ordered_product
        $query_buy = "INSERT INTO `tb_ordered_product`(`id_user`, `id_product`, `id_address`,`date`, `random_code`) 
                        VALUES (?,?,?,?,?)";
        $query_buy = $db->getConnection()->prepare($query_buy);
        $query_buy->bind_param("sssss", $this->user_id, $this->product_id, $this->address_id, $date, $random_code);

        if ($query_buy->execute()) {
            //delete data from tb_cart
            $query_delete = "DELETE FROM `tb_cart` WHERE id_user = ?";
            $query_delete = $db->getConnection()->prepare($query_delete);
            $query_delete->bind_param("s", $this->user_id);
            $query_delete->execute();
            
            echo "<script>
                alert('Checkout berhasil');
            </script>";
            header("Location: checkout.php");
        } else {
            echo "<script>
            alert('Checkout gagal');
        </script>";
            header("Location: cart.php");
        }
    }
}
