#Variables CloudFoundry à modifier en fonction du projet
$org = "dm-pode-staging"
$space = "METIER GESTION RESSOURCE CLIENT"
$app = "site-metier-grc"

if ($org -eq '')
{
    Write-Host "Merci de renseigner l'ORG dans le script."
    exit 1
}
if ($space -eq '')
{
    Write-Host "Merci de renseigner le SPACE dans le script."
    exit 1
}
if ($app -eq '')
{
    Write-Host "Merci de renseigner l'APP dans le script."
    exit 1
}

Write-Host "Information d'identification a CloudFoundry`n"

# Prompt login variables
$username = Read-Host -Prompt "Saisir votre identifiant"
Do
{
    $password = Read-Host -Prompt "Saisir votre mot de passe"
    $confirm = Read-Host -Prompt "Confirmer votre mot de passe"

} Until(($password -eq $confirm) -eq $True)

Write-Host "Compilation du FrontEnd pour déploiement en production"
# Compile and build js
npm run build


# Initialize the loop
$endpoints = @("https://api.ep1.mercury.si.fr.intraorange", "https://api.ep2.mercury.si.fr.intraorange", "https://api.ep3.mercury.si.fr.intraorange")

foreach ($endpoint in $endpoints)
{
    Write-Host "Deploiement sur $endpoint"
    #Login
    cf login --skip-ssl-validation -a $endpoint -o $org -s $space -u $username -p $password

    #Create app
    cf create-app $app

    #Push code and Run migration task
    cf push -f manifest.yml --strategy rolling #deploy code without interrupt
    cf run-task $app -m 256M -k 750M -c "php ./bin/console doctrine:migrations:migrate -n --allow-no-migration --all-or-nothing" #launch migrations

    $continue = Read-Host -Prompt "Voulez-vous continuer le deploiement sur un autre entpoint ? o/n";
    if ($continue -eq 'o')
    {
        continue
    }
    break
    Write-Host "`nFin du script"
}
