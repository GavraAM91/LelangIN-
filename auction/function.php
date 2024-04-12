<?php

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
}

class auction
{
    protected $ammount_auction, $id_user, $id_product;

    public function __construct($ammount_auction = "ammount_auction", $id_user = "id_user", $id_product = "id_product")
    {
        $this->ammount_auction = $ammount_auction;
        $this->id_user = $id_user;
        $this->id_product = $id_product;
    }

    public function addAuction()
    {
        $db = new database();

        $sql = $db->getConnection()->prepare("INSERT INTO `tb_auction` (`id_user`, `id_product`, `price`) VALUES (?,?,?)");

        $sql->bind_param("sss", $this->id_user,  $this->id_product, $this->ammount_auction);

        if ($sql->execute()) {
            echo "<script> 
                alert('Data Auction berhasil ditambahkan');
            </scrit>";
            header("Location: ../index.php");
        } else {
            echo "<script> 
            alert('Data Auction berhasil ditambahkan');
            </scrit>";
            header("Location: ../index.php");
        }
    }

    public function showData()
    {
        $db = new database();

        $sql_auction = $db->getConnection()->query("SELECT * FROM tb_auction ORDER BY price DESC LIMIT 1");
        $auction_data = $sql_auction->fetch_array();

        // if ($auction_price == null) {
        //     $auction_price['price'] = 0;
        // }
    }
} 

class address {
    private $id_user,
    $desa,
    $kecamatan, 
    $kota,
    $provinsi,
    $negara;

    public function __construct ($id_user = "id_user", $desa = "desa", $kecamatan = "kecamatan", $kota = "kota", $provinsi = "provinsi", $negara = "negara") {
        $this->id_user = $id_user;
        $this->desa = $desa;
        $this->kecamatan = $kecamatan;
        $this->kota = $kota;
        $this->provinsi = $provinsi;
        $this->negara = $negara;
    }

    public function addAddress() {
        //open database or make new database class
        $db = new database();

        //make query insert into database
        $sql = "INSERT INTO `tb_address`(`id_user`, `desa`, `kecamatan`, `kabupaten/kota`, `provinsi`, `negara`) 
                VALUES (?,?,?,?,?,?)";
        $query = $db->getConnection()->prepare($sql);
        $query->bind_param("ssssss", $this->id_user, $this->desa, $this->kecamatan, $this->kota, $this->provinsi, $this->negara);
        $query->execute();

        if($query) {
            echo "<script>
                alert('data alamat berhasil ditambahkan');
            </script>";
            header("Location: ../index.php");
        } else {
            echo "<script>
                alert('data alamat berhasil ditambahkan');
            </script>";
            header("Location: ../index.php");
        }
    }

    public function editAdress() {
        //open database
        $db = new database();

        //make query update into database
        $sql = "UPDATE `tb_address` SET `id_user`=?,`desa`=?,`kecamatan`=?,`kabupaten/kota`=?,`provinsi`=?,`negara`=? WHERE `id_user`=?";
        $query = $db->getConnection()->prepare($sql);
        $query->bind_param("sssssss", $this->id_user, $this->desa, $this->kecamatan, $this->kota, $this->provinsi, $this->negara, $this->id_user);
        $query->execute();

        if($query) {
            echo "<script>
                alert('data alamat berhasil ditambahkan');
            </script>";
            header("Location: ../index.php");
        } else {
            echo "<script>
                alert('data alamat berhasil ditambahkan');
            </script>";
            header("Location: ../index.php");
        }
    }
}
    