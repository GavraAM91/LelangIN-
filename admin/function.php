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
    public function __construct($id = "id", $image = "image", $name = "name", $description = "description", $quantity=1 ,$price = "price")
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
        //set date 
        date_default_timezone_set("Asia/Jakarta");
        $date = date("Y-m-d h-m-s");

        //deklarasi database
        $db = new database();
        $lastIDFromDB = $db->getLastProductID(); // Dapatkan lastID dari database
        $generator = new IDGenerator($lastIDFromDB); // Inisialisasi IDGenerator dengan lastID
        $newID = $generator->generateID(); // Generate ID baru

        $ukuran_file = $_FILES['image']['size'];
        $error = $_FILES['image']['error'];
        $tmp_file = $_FILES['image']['tmp_name'];

        if ($error === 4) {
            echo "<script>alert('pilih gambar terlebih dahulu!');</script>";
            header("Location: product.php");
            return false;
        }

        $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
        $ekstensiGambar = explode('.', $this->image);
        $ekstensiGambar = strtolower(end($ekstensiGambar));

        if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
            echo "<script>alert('yang anda upload bukan gambar!');</script>";
            return false;
        }

        if ($ukuran_file > 5242880) {
            echo "<script>alert('ukuran terlalu besar!');</script>";
            header("Location: product.php");
            exit;
        }

        //jika ingin data gambar diacak
        // $newImage = uniqid() . '.' . $ekstensiGambar;
        // $storage = '../data_image/' . $newImage;

        // Jika ingin data gambar tetep sama dengan yang diinput
        $targetDir = '../data_image/';
        $targetFile = $targetDir . basename($this->image);

        // if (move_uploaded_file($tmp_file, $storage)) {s
        if (move_uploaded_file($tmp_file, $targetFile)) {
            echo "<script>
                alert('data berhasil ditambahkan!';
            </script>";

            $sql = $db->getConnection()->prepare("INSERT INTO `tb_product`(`id_product`, `image`, `name`, `description`,`quantity`,`price`,`date_added`)  
                VALUES (?,?,?,?,?,?,?)");

            $sql->bind_param('sssssss', $newID, $this->image, $this->name, $this->description,$this->quantity,$this->price,$date);

            // $sql = $db->getConnection()->query("INSERT INTO `tb_product`(`id_product`, `image`, `name`, `description`, `quantity`, `price`,`date_added`)  
            //     VALUES ('$newID', '$this->image', '$this->name', '$this->description', '$this->quantity', '$this->price', '$date')");

            if ($sql->execute()) {
                echo "<script>
                        alert('input product berhasil!');
                        </scrxipt>";
                header('Location: product.php');
            } else {
                echo "<script>
                        alert('Terjadi kesalahan input product');
                    </script>";
                header("Location: product.php");
            }
        }
    }

    public function editProduct()
    {
        $db = new database();

        $ukuran_file = $_FILES['image']['size'];
        $error = $_FILES['image']['error'];
        $tmp_file = $_FILES['image']['tmp_name'];

        // var_dump($image);
        // exit;
        if ($error === 4) {
            echo "<script>alert('pilih gambar terlebih dahulu!');</script>";
            header("Location: product.php");
            return false;
        }

        $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
        $ekstensiGambar = explode('.', $this->image);
        $ekstensiGambar = strtolower(end($ekstensiGambar));

        if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
            echo "<script>alert('yang anda upload bukan gambar!');</script>";
            header("Location: product.php");
            return false;
        }

        if ($ukuran_file > 5242880) {
            echo "<script>alert('ukuran terlalu besar!');</script>";
            header("Location: product.php");
            exit;
        }

        //jika pingin nama gambar di acak
        // $newImage = uniqid() . '.' . $ekstensiGambar;
        // $storage = '../data_image/' . $newImage;

        //jika ingin nama gambar sama seperti yang diinput
        $targetDir = '../data_image/';
        $targetFile = $targetDir . basename($this->image);

        // gunakan ini jika ingin data gambar diacak
        // if (move_uploaded_file($tmp_file, $storage)) {
        if (move_uploaded_file($tmp_file, $targetFile)) {
            echo "<script>
               </script>";
        } else {
            echo "Gagal mengunggah gambar. Kode Kesalahan: " . $error;
            echo "<br><a href='product.php'>Kembali Ke Form</a>";
        }

        //for time 
        date_default_timezone_set("Asia/Jakarta");
        $date = date("Y-m-d H:i:s");

        //jika image empty atau kosong
        if (empty($this->image)) {
            $sql = $db->getConnection()->prepare("UPDATE `tb_product` SET `name`=?,`description`=?,`quantity`=?,`price`=? WHERE id_product=? ");
            $sql->bind_param('ssssss', $this->name, $this->description,$this->quantity, $this->price, $this->id);
        } else {
            //jika image berisi
            //update data
            $sql = $db->getConnection()->prepare("UPDATE `tb_product` SET `image`=?,`name`=?,`description`=?,`quantity`=?,`price`=? WHERE id_product=? ");
            $sql->bind_param('sssss', $this->image, $this->name, $this->description, $this->quantity, $this->price, $this->id);
        }
        // $sql = $db->getConnection()->query("UPDATE `tb_product` SET `image`='$this->image',`name`='$this->name',`description`='$this->description',
        //         `quantity`='$this->quantity',`price`='$this->price',`date_added`='$date' WHERE id_product='$this->id'");

        if ($sql->execute()) {
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

class timer
{
    private $id_product, $date, $hour, $minute, $second;

    public function __construct($id_product = null, $date = null, $hour = null, $minute = null, $second = null)
    {
        $this->id_product = $id_product;
        $this->date = $date;
        $this->hour = $hour;
        $this->minute = $minute;
        $this->second = $second;
    }

    public function time()
    {
        $db = new database();
        $sql = $db->getConnection()->prepare("SELECT * FROM tb_countdown WHERE id_product = ?");
        $sql->bind_param("s", $this->id_product);
        $sql->execute();
        $data = $sql->get_result();


        if ($data->num_rows == 0 ) {

            $sql = "INSERT INTO `tb_countdown` (`id_product`, `date`, `hour`, `minute`, `second`) 
                    VALUES (?,?,?,?,?)";
            $query = $db->getConnection()->prepare($sql);
            $query->bind_param("sssss", $this->id_product, $this->date, $this->hour, $this->minute, $this->second);

            if ($query->execute()) {
                echo "<script>
                alert('Tambah Countdown berhasil!');
            </script>";
                header("Location: product.php");
            }
        } else {
            $query = "UPDATE `tb_countdown` SET `id_product`=?, `date`= ?,
                `hour`=?,`minute`=?,`second`=? WHERE id_product = ?";
            $sql = $db->getConnection()->prepare($query);
            $sql->bind_param("ssssss", $this->id_product, $this->date, $this->hour, $this->minute, $this->second, $this->id_product);
            $sql->execute();
            if ($query) {
                echo "<script>
                alert('Update countdown berhasil!');
            </script>";
                header("Location: product.php");
            }
        }
    }
    public function showTime()
    {
        $db = new database();

        $query = $db->getConnection()->query("SELECT * FROM tb_countdown ORDER BY id_product DESC 1");
        $data = $query->fetch_assoc();

        // if($data)
        // {
        //     $this->date = $data['date'];
        //     $this->hour = $data['hour'];
        //     $this->minute = $data['minute'];
        //     $this->second = $data['second'];
        // }
        $result = [];
        foreach ($query as $data) {
            $result[] = $data;
        }
        return $result;
    }
}
