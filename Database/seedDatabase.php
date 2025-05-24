<?php
// seedDatabase.php

include '../connection.php'; // Adjust the path as needed

$songs = [
    [
        'title' => 'Alone',
        'artist' => 'Alan Walker',
        'genre' => 'Electro house',
        'release_year' => 2016,
        'duration' => 164,
        'file_path' => 'Alan_Walker_-_Alone.mp3',
        'cover_path' => 'Alan_Walker_-_Alone.jpg'
    ],
    [
        'title' => 'Alone, Pt. II',
        'artist' => 'Alan Walker & Ava Max',
        'genre' => 'Moombahton',
        'release_year' => 2019,
        'duration' => 179,
        'file_path' => 'Alan_Walker_Ava_Max_-_Alone_Pt_II.mp3',
        'cover_path' => 'Alan_Walker_Ava_Max_-_Alone_Pt_II.jpg'
    ],
    [
        'title' => 'Faded',
        'artist' => 'Alan Walker',
        'genre' => 'Electro house',
        'release_year' => 2015,
        'duration' => 212,
        'file_path' => 'Alan_Walker_-_Faded.mp3',
        'cover_path' => 'Alan_Walker_-_Faded.jpg'
    ],
    [
        'title' => 'The Spectre',
        'artist' => 'Alan Walker',
        'genre' => 'Electro house',
        'release_year' => 2017,
        'duration' => 193,
        'file_path' => 'Alan_Walker_-_The_Spectre.mp3',
        'cover_path' => 'Alan_Walker_-_The_Spectre.jpg'
    ],
    [
        'title' => 'PLAY (Alan Walker x Niya Remix)',
        'artist' => 'Alan Walker, K-391, Tungevaag, Mangoo',
        'genre' => 'Electro house',
        'release_year' => 2019,
        'duration' => 167,
        'file_path' => 'Alan_Walker_K391_Tungevaag_Mangoo_-_PLAY_Remix.mp3',
        'cover_path' => 'Alan_Walker_K391_Tungevaag_Mangoo_-_PLAY_Remix.jpg'
    ],
    [
        'title' => 'On My Way',
        'artist' => 'Alan Walker, Sabrina Carpenter & Farruko',
        'genre' => 'Moombahton',
        'release_year' => 2019,
        'duration' => 193,
        'file_path' => 'Alan_Walker_Sabrina_Carpenter_Farruko_-_On_My_Way.mp3',
        'cover_path' => 'Alan_Walker_Sabrina_Carpenter_Farruko_-_On_My_Way.jpg'
    ],
    [
        'title' => 'So Am I',
        'artist' => 'Ava Max',
        'genre' => 'Electropop',
        'release_year' => 2019,
        'duration' => 183,
        'file_path' => 'Ava_Max_-_So_Am_I.mp3',
        'cover_path' => 'Ava_Max_-_So_Am_I.jpg'
    ],
    [
        'title' => 'I Want It That Way',
        'artist' => 'Backstreet Boys',
        'genre' => 'Pop',
        'release_year' => 1999,
        'duration' => 213,
        'file_path' => 'Backstreet_Boys_-_I_Want_It_That_Way.mp3',
        'cover_path' => 'Backstreet_Boys_-_I_Want_It_That_Way.jpg'
    ],
    [
        'title' => 'Done For Me',
        'artist' => 'Charlie Puth feat. Kehlani',
        'genre' => 'Contemporary R&B',
        'release_year' => 2018,
        'duration' => 180,
        'file_path' => 'Charlie_Puth_feat_Kehlani_-_Done_For_Me.mp3',
        'cover_path' => 'Charlie_Puth_feat_Kehlani_-_Done_For_Me.jpg'
    ],
    [
        'title' => 'How Long',
        'artist' => 'Charlie Puth',
        'genre' => 'Pop-funk',
        'release_year' => 2017,
        'duration' => 198,
        'file_path' => 'Charlie_Puth_-_How_Long.mp3',
        'cover_path' => 'Charlie_Puth_-_How_Long.jpg'
    ],
    [
        'title' => 'Good Life',
        'artist' => 'Kehlani & G-Eazy',
        'genre' => 'Pop-rap',
        'release_year' => 2017,
        'duration' => 225,
        'file_path' => 'Kehlani_G_Eazy_-_Good_Life.mp3',
        'cover_path' => 'Kehlani_G_Eazy_-_Good_Life.jpg'
    ],
    [
        'title' => 'Show You',
        'artist' => 'Shawn Mendes',
        'genre' => 'Pop',
        'release_year' => 2014,
        'duration' => 180,
        'file_path' => 'Shawn_Mendes_-_Show_You.mp3',
        'cover_path' => 'Shawn_Mendes_-_Show_You.jpg'
    ]
];


$stmt = $connect->prepare("INSERT INTO songs (title, artist, genre, release_year, duration, file_path, cover_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssissss", $title, $artist, $genre, $release_year, $duration, $file_path, $cover_path);

foreach ($songs as $song) {
    $title = $song['title'];
    $artist = $song['artist'];
    $genre = $song['genre'];
    $release_year = $song['release_year'];
    $duration = $song['duration'];
    $file_path = $song['file_path'];
    $cover_path = $song['cover_path'];

    $stmt->execute();
}

echo "Seeding complete.";

$stmt->close();
$connect->close();
?>
