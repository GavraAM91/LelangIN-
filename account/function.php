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
        return "USR" . str_pad($this->lastID, 3, "0", STR_PAD_LEFT);
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

    public function getLastUserID()
    {
        $this->conn = $this->getConnection();
        $result = $this->conn->query("SELECT id_user FROM tb_account ORDER BY id_user DESC LIMIT 1");
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastID = $row['id_user'];
            return intval(substr($lastID, 3)); // Mengembalikan hanya bagian numerik dari ID
        } else {
            return 0; // Jika tidak ada entri, mulai dari 0
        }
    }
}


class account
{
    private $image, $username, $password, $confirmPassword, $email, $checkbox, $id_user;

    public function __construct($image = "image", $username = "username", $password = "password", $confirmPassword = "confirmPassword", $email = "email", $checkbox = "checkbox", $id_user = "id_user")
    {
        $this->image = $image;
        $this->username = $username;
        $this->password = $password;
        $this->confirmPassword = $confirmPassword;
        $this->email = $email;
        $this->checkbox = $checkbox;
        $this->id_user = $id_user;
    }

    //login 
    public function login()
    {
        $db = new database();
        $conn = $db->getConnection(); // Dapatkan koneksi database

        // Siapkan query untuk mencari user berdasarkan username
        $query = $conn->prepare("SELECT * FROM tb_account WHERE username = ?");
        $query->bind_param("s", $this->username);
        $query->execute();
        $result = $query->get_result();

        $password = $this->password;

        session_start();
        // Cek jika user ditemukan
        if ($user = $result->fetch_assoc()) {
            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                //jika chcekbox bernilai 1;
                if ($this->checkbox == 1) {
                    //buat cookie
                    setcookie('id', $user['id_account'], time() + 600);
                    setcookie('key', hash('sha256', $user['username']));
                }

                // Password benar, login berhasil
                $_SESSION['role'] = $user['role'];

                //set id into session
                $_SESSION['id_user'] = $user['id_user'];

                // Redireksi berdasarkan role
                if ($user['role'] == 'admin') {

                    echo "<script>
                        alert('LOGIN BERHASIL!');
                    </script>";

                    $_SESSION['username'] = $this->username;
                    header('Location: ../admin/index.php');
                    exit();
                } else if ($user['role'] == 'user') {
                    echo "<script>
                        alert('LOGIN BERHASIL!');
                    </script>";

                    $_SESSION['username'] = $this->username;
                    header('Location: ../index.php');
                    exit(); // Menambahkan exit setelah header
                }
            } else {
                // Password salah
                echo "<script>
                    alert('Password salah!');
                    </script>";
                return false;
            }
        } else {
            // Username tidak ditemukan
            echo "<script>
                alert('Username tidak ditemukan!');
                </script>";
            return false;
        }
    }

    public function signup()
    {

        $db = new database();
        $lastIDFromDB = $db->getLastUserID(); // Dapatkan lastID dari database

        $generator = new IDGenerator($lastIDFromDB); // Inisialisasi IDGenerator dengan lastID
        $newID = $generator->generateID(); // Generate ID baru

        //check username sudah ada atau belum
        $query = $db->getConnection()->query("SELECT username FROM tb_account WHERE username = '$this->username'");
        if ($query->fetch_assoc()) {
            echo "<script>
            alert('username sudah digunakan');
        </script>";
            return false;
        }

        //check email sudah ada atau belum
        $query = $db->getConnection()->query("SELECT username FROM tb_account WHERE email = '$this->email'");
        if ($query->fetch_assoc()) {
            echo "<script>
            alert('Email sudah digunakan');
        </script>";
            return false;
        }

        // var_dump($this->password);
        // var_dump($this->confirmPassword);
        // exit;

        // Konfirmasi password
        if ($this->password != $this->confirmPassword) {
            echo "<script>
        alert('Password tidak sama!');
        </script>";
            return false;
        }


        // Hash password
        $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

        $ukuran_file = $_FILES['image']['size'];
        $error = $_FILES['image']['error'];
        $tmp_file = $_FILES['image']['tmp_name'];

        if ($error === 4) {
            echo "<script>alert('pilih gambar terlebih dahulu!');</script>";
            header("Location: signup.php");
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
        $targetDir = '../profile_image/';
        $targetFile = $targetDir . basename($this->image);

        // if (move_uploaded_file($tmp_file, $storage)) {s
        if (move_uploaded_file($tmp_file, $targetFile)) {
            echo "<script>
                alert('data berhasil ditambahkan!';
            </script>";

            $sql = $db->getConnection()->prepare("INSERT INTO tb_account (id_user, image, username, password, email) VALUES (?, ?, ?, ?, ?)");
            $sql->bind_param("sssss", $newID, $this->image, $this->username, $password_hash, $this->email);
            // Menambahkan ke database
            if ($sql->execute()) {
                header('Location: login.php');
                echo "<script>
                alert('Pendaftaran berhasil!');
                </script>";
                exit;
            } else {
                echo "<script>
            alert('Terjadi kesalahan saat mendaftar.');
            </script>";
                return false;
            }
        }
    }

    public function logout()
    {
        $db = new database();

        //open query 
        
        //delete SESSION
        session_start();
        $_SESSION = [];
        session_unset();
        session_destroy();

        //delete COOKIE
        setcookie('id', '', time() - 3600);
        setcookie('key', '', time() - 3600);

        header('location: signup.php');
    }

    public function editAccount()
    {
        $db = new database();

        //open database
        $data_test = $db->getConnection()->query("SELECT * FROM tb_account");

        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);

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
        $targetDir = '../profile_image/';
        $targetFile = $targetDir . basename($this->image);

        // gunakan ini jika ingin data gambar diacak
        // if (move_uploaded_file($tmp_file, $storage)) {
        if (move_uploaded_file($tmp_file, $targetFile)) {
            echo "<script>
                    alert('berhasil mengunggah gambar!');
               </script>";
        } else {
            echo "Gagal mengunggah gambar. Kode Kesalahan: " . $error;
            echo "<br><a href='profile_page.php'>Kembali Ke Form</a>";
        }

        //edit profile 
        if (empty($this->image)) {
            $query_edit = "UPDATE `tb_account` SET `username`=?,`password`=?',`email`=? WHERE `id_user`=?";
            $query_edit = $db->getConnection()->prepare($query_edit);
            $query_edit->bind_param("ssss", $this->username, $hashed_password, $this->email, $this->id_user);
        } else {
            $query_edit = "UPDATE `tb_account` SET `image`=?,`username`=?,`password`=?,`email`=? WHERE `id_user`=?";
            $query_edit = $db->getConnection()->prepare($query_edit);
            $query_edit->bind_param("sssss", $this->image, $this->username, $hashed_password, $this->email, $this->id_user);
        }

        if ($query_edit->execute()) {
            echo "<script>
                alert('data berhasil di update');
            </script>";
            return true;
        } else {
            echo "<script>
                alert('data gagal di update');
            </script>";
            return false;
        }
    }
}

class account_setting
{
    private $username,
        $password;

    public function __construct($username = "username", $password = "password")
    {
        $this->username = $username;
        $this->password = $password;
    }
}
