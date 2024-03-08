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
        return "PRD" . str_pad($this->lastID, 3, "0", STR_PAD_LEFT);
    }
}

class database
{
    private $host = "localhost"; // Host database
    private $db_name = "db_lelang"; // Nama database
    private $username = "root"; // Username daltabase
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
    public function getLastProductID()
    {
        $this->conn = $this->getConnection();
        $result = $this->conn->query("SELECT id_product FROM tb_product ORDER BY id_product DESC LIMIT 1");
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastID = $row['id_product'];
            return intval(substr($lastID, 3)); // Mengembalikan hanya bagian numerik dari ID
        } else {
            return 0; // Jika tidak ada entri, mulai dari 0
        }
    }
}

class product
{
    private $id, $image, $name, $description, $quantity, $price;

    //construct
    public function __construct($id="id" ,$image = "image", $name = "name", $description = "description", $quantity = "quantity", $price = "price")
    {
        $this->id = $id;
        $this->image = $image;
        $this->name = $name;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    public function addProduct()
    {
        $db = new database();
        $lastIDFromDB = $db->getLastProductID(); // Dapatkan lastID dari database

        $generator = new IDGenerator($lastIDFromDB); // Inisialisasi IDGenerator dengan lastID
        $newID = $generator->generateID(); // Generate ID baru

        //for time 
        date_default_timezone_set("Asia/Jakarta");
        $date = date("Y-m-d H:i:s");

        // $sql = $db->getConnection()->prepare("INSERT INTO `tb_product`(`id_product`, `image`, `name`, `description`, `quantity`, `price`,`date_added`)  
        //     VALUES (?,?,?,?,?,?,?)");

        // $sql->bind_param('sssssss', $newID, $this->image, $this->name, $this->description, $this->quantity, $this->price, $date);

        $sql = $db->getConnection()->query("INSERT INTO `tb_product`(`id_product`, `image`, `name`, `description`, `quantity`, `price`,`date_added`)  
        VALUES ('$newID', '$this->image', '$this->name', '$this->description', '$this->quantity', '$this->price', '$date')");

        if ($sql) {
            echo "<script>
        alert('input product berhasil!');
        </script>";
            header('Location: product.php');
        } else {
            echo "Error: ";
            echo "<script>
                alert('Terjadi kesalahan input product');
            </script>";
        }
    }

    public function editProduct()
    {
        $db = new database();

        //for time 
        date_default_timezone_set("Asia/Jakarta");
        $date = date("Y-m-d H:i:s");

        // $sql = $db->getConnection()->prepare("UPDATE `tb_product` SET `image`=?,`name`=?,`description`=?,`quantity`=?,`price`=?,`date_added`=? WHERE id_product=? ");
        // $sql->bind_param('sssssss', $this->image, $this->name, $this->description, $this->quantity, $this->price, $date, $this->id);

         $sql = $db->getConnection()->query("UPDATE `tb_product` SET `image`='$this->image',`name`='$this->name',`description`='$this->description',`quantity`='$this->quantity',`price`='$this->price',`date_added`='$date' WHERE id_product='$this->id'");
        if ($sql) {
            echo "<script>
                alert('Data berhasil di update');
            </script>";

            header("Location: product.php");
            exit;
        } else {
            echo "<script>
                alert('Data gagal di update');
            </script>";

            header("Location: product.php");
            exit;
        }
    }

    public function deleteProduct()
    {
        $db = new database();

        $query = "DELETE FROM `tb_product` WHERE id_product = '$this->id'";
        $result = $db->getConnection()->query($query);

        if ($result) {
            echo "<script>
                alert('Delete data berhasil!');
            </script>";
            header("Location: product.php");
        } else {
            "<script>
                alert('Delete data gagal!');
            </script>";
            header("Location: product.php");
            return false;
        }
    }
}
