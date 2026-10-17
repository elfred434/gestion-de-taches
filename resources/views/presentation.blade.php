<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma présentation</title>
</head>
<body>
    <h1>Bonjour, je suis {{ $prenom }}</h1>
    <p>J'apprends à créer des applications avec Laravel.</p>
    <p>Mon objectif est de :{{ $objectif }}</p>
    <br>
    <h2>Mes tâches</h2>
    <ul>
        @foreach ($taches as $task )
            <li>{{ $task }}</li>
        @endforeach
    </ul>
    
</body>
</html>