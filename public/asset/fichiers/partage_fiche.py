sudo apt update
sudo apt install cifs-utils smbclient

smbclient -L //192.168.1.10 -U utilisateur_windows

#!/usr/bin/env python3

import os
import shutil
import subprocess
from pathlib import Path

MACHINES = [
    "192.168.1.10",
    "192.168.1.11",
    "192.168.1.12",
    "192.168.1.13",
    "192.168.1.14",
    "192.168.1.15",
    "192.168.1.16",
    "192.168.1.17",
    "192.168.1.18",
    "192.168.1.19",
]

USER = "administrateur"
PASSWORD = "motdepasse"

DESTINATION = "/home/ddpp/Documents/fiches_collecte_aalfa"

for ip in MACHINES:

    point_montage = f"/mnt/{ip}"

    os.makedirs(point_montage, exist_ok=True)

    try:
        subprocess.run(
            [
                "mount",
                "-t",
                "cifs",
                f"//{ip}/Documents",
                point_montage,
                "-o",
                f"username={USER},password={PASSWORD}"
            ],
            check=True
        )

        for root, dirs, files in os.walk(point_montage):

            for fichier in files:

                nom = fichier.upper()

                if nom.endswith("FC.PDF"):

                    source = os.path.join(root, fichier)
                    destination = os.path.join(
                        DESTINATION,
                        f"{ip}_{fichier}"
                    )

                    shutil.copy2(source, destination)

        subprocess.run(
            ["umount", point_montage],
            check=True
        )

    except Exception as e:
        print(f"Erreur sur {ip}: {e}")



rsync -av

chmod +x collecte_fc.py

crontab -e

0 * * * * /usr/bin/python3 /home/ddpp/scripts/collecte_fc.py >> /home/ddpp/logs/collecte_fc.log 2>&1

if not os.path.exists(destination):
    shutil.copy2(source, destination)



comment Créer un dossier partagé central sur Ubuntu et le Monter ce partage sur les 10 postes Windows ?


sudo apt update
sudo apt install samba

sudo systemctl status smbd

sudo chown -R ddpp:ddpp /home/ddpp/Documents/fiches_collecte_aalfa
sudo chmod -R 775 /home/ddpp/Documents/fiches_collecte_aalfa

sudo nano /etc/samba/smb.conf

[FichesCollecte]
    path = /home/ddpp/Documents/fiches_collecte_aalfa
    browseable = yes
    writable = yes
    read only = no
    guest ok = no
    create mask = 0664
    directory mask = 0775

sudo smbpasswd -a ddpp

sudo smbpasswd -e ddpp

sudo testparm

sudo systemctl restart smbd
sudo systemctl enable smbd

sudo ufw allow samba
sudo ufw reload

smbclient -L localhost -U ddpp

\\192.168.1.100\FichesCollecte

Ce PC
 → Connecter un lecteur réseau

Lettre : Z:

Reconnecter à l'ouverture de session

Utilisateur : ddpp
Mot de passe : ********

net use Z: \\192.168.1.100\FichesCollecte /user:ddpp MonMotDePasse /persistent:yes

ls /home/ddpp/Documents/fiches_collecte_aalfa


find /FichesCollecte -type f -iname "*.pdf" -exec mv {} /home/tchos/Documents/projets/symfony/aalfa/var/fiches_collecte/ \;

crontab -e

*/1 * * * * find /home/ddpp/Documents/fiches_collecte_aalfa -type f -iname "*FC.pdf" -exec mv {} /home/tchos/Documents/projets/symfony/aalfa/var/fiches_collecte_aalfa/ \; >> /home/tchos/deplacement_pdf.log 2>&1




#!/usr/bin/env python3

import os
import shutil
from datetime import datetime

SOURCE = "/home/ddpp/Documents/fiches_collecte_aalfa"
DESTINATION = "/var/www/aalfa.minfi.cm/var/fiches_collecte_aalfa"
LOG_FILE = "/home/tchos/deplacement_pdf.log"

def log(message):
    with open(LOG_FILE, "a", encoding="utf-8") as f:
        f.write(f"[{datetime.now()}] {message}\n")

# Vérification du dossier destination
os.makedirs(DESTINATION, exist_ok=True)

nb_fichiers = 0

for root, dirs, files in os.walk(SOURCE):

    for fichier in files:

        # Recherche des fichiers se terminant par _FC.pdf
        if fichier.upper().endswith("_FC.PDF"):

            source_file = os.path.join(root, fichier)
            destination_file = os.path.join(DESTINATION, fichier)

            try:

                # Si le fichier existe déjà dans la destination
                if os.path.exists(destination_file):

                    base, ext = os.path.splitext(fichier)

                    destination_file = os.path.join(
                        DESTINATION,
                        f"{base}_{datetime.now().strftime('%Y%m%d_%H%M%S')}{ext}"
                    )

                shutil.move(source_file, destination_file)

                nb_fichiers += 1

                log(f"Déplacé : {source_file} -> {destination_file}")

            except Exception as e:

                log(f"ERREUR : {source_file} : {str(e)}")

log(f"Fin du traitement - {nb_fichiers} fichier(s) déplacé(s)")

chmod +x /home/tchos/scripts/deplacement_fc.py

*/1 * * * * /usr/bin/python3 /var/www/aalfa.minfi.cm/var/fiches_collecte_aalfa/deplacement.py

tail -f /home/tchos/deplacement_pdf.log
