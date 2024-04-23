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
        return "ADRS" . str_pad($this->lastID, 3, "0", STR_PAD_LEFT);
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
        $query_data->bind_param("sssssss", $newID, $this->id_user, $this->desa, $this->kecamatan, $this->kota, $this->provinsi, $this->negara);
        $query_data = $query_data->execute();

        if ($query_data) {
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
        $query_data = "UPDATE `tb_address` SET `desa`=?,`kecamatan`=?,`kabupaten/kota`=?,`provinsi`=?,`negara`=?";
        $query_data = $db->getConnection()->prepare($query_data);
        $query_data->bind_param("sssss", $this->desa, $this->kecamatan, $this->kota, $this->provinsi, $this->negara);

        if ($query_data->execute()) {
            echo "<script>
            alert('data berhasil di update ');
            </script>";
            header("Location: ../index.php");
        } else {
            echo "<script>
            alert('data gagal di update ');
        </script>";
            header("Location: ../index.php");
            return false;
        }
    }
}
