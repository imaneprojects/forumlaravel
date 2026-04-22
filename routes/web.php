<?php

//importe les controlleurs
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Routes pour l'enregistrement des utilisateurs
Route::get('/register', [RegisterController::class, 'create'])->name('register');//Affiche formulaire inscription.
Route::post('/register', [RegisterController::class, 'store']);//Quand user clique sinscrire (validation, insertion user en base)

// Routes pour l'authentification
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');//affiche page login
Route::post('/login', [LoginController::class, 'login']);//verifie (email, password)puis connecte user
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');//supprime session utilisateur

Route::middleware(['auth'])->group(function () {    /**Toutes les routes dedans nécessitent connexion. */
    Route::get('/profile', [ProfilController::class, 'show'])->name('profile.show'); //Affiche profil utilisateur connecté
    Route::get('/profile/{user}', [ProfilController::class, 'showUser'])->name('profile.showUser'); //Affiche profil dun autre user
    Route::post('/profile/{user}/follow', [ProfilController::class, 'follow'])->name('profile.follow');//Ajoute relation followers
    Route::delete('/profile/{user}/unfollow', [ProfilController::class, 'unfollow'])->name('profile.unfollow');//Supprime relation
    Route::delete('/profile/follower/{follower}', [ProfilController::class, 'removeFollower']) //Quelqu’un je suit → je peux le retirer.
    ->name('profile.removeFollower');
    Route::get('/profile/{user}/edit', [ProfilController::class, 'edit'])->name('profile.edit');//Affiche formulaire edit.
    Route::put('/profile/{user}', [ProfilController::class, 'update'])->name('profile.update');//update profil(nom,email,..)
    Route::post('/profile/{user}/image', [ProfilController::class, 'updateImage'])->name('profile.updateImage');//upload photo profil et cover
});

// Topics corrg
Route::get('/', [PostController::class, 'index2'])->name('topics.index2');//page principale(liste topics)
Route::get('/create', [PostController::class, 'create'])->name('topics.create');//Affiche page creer topic
Route::post('/', [PostController::class, 'store'])->name('topics.store');//ajoute topic en base

Route::get('/topics/search', [PostController::class, 'search'])->name('topics.search');//Recherche topics par mot-clé
Route::get('/topics/{id}', [PostController::class, 'show'])->name('topics.show');//page detail de topic(contenu,auteur,commentaires,...)
Route::get('/topics/{id}/edit', [PostController::class, 'edit'])->name('topics.edit');//formulaire edit
Route::put('/topics/{id}', [PostController::class, 'update'])->name('topics.update');//update topic
Route::delete('/topics/{id}', [PostController::class, 'destroy'])->name('topics.destroy');//delete topic

//topics like
Route::post('/topics/{id}/like', [PostController::class, 'toggleLike'])->name('topics.like')->middleware('auth');//ajoute ou retire like

// Routes pour les commentaires
Route::post('/commentaires/{topic}', [CommentaireController::class, 'store'])->name('commentaires.store');//ajoute commentaire au topic
Route::delete('/topics/{topic}/commentaires/{commentaire}', [CommentaireController::class, 'destroy'])//supprime commentaire
    ->name('commentaires.destroy');

// Routes pour les thèmes
Route::post('/themes', [ThemeController::class, 'store'])->name('themes.store');//creer nouveau theme
Route::get('/themes/create', [ThemeController::class, 'create'])->name('themes.create');//formulaire pour ajouter dun nv theme

// Routes pour les notifications
Route::get('/notifications', [NotificationController::class, 'index'])
    ->name('notifications.index');//liste notifications

Route::get('/notifications/json', [NotificationController::class, 'getNotifications'])
    ->name('notifications.json');//Utilisé JS / AJAX

Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);//marquer lu
