username=$1
password=$2

encrypted_password=$(openssl passwd -1 $password)

sudo useradd -m -p $encrypted_password $username

echo "L'utilisateur $username a été créé"