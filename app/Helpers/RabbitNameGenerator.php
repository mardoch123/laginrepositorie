<?php

namespace App\Helpers;

class RabbitNameGenerator
{
    // Noms pour lapins mâles
    protected static $maleNames = [
        'Oreo', 'Thumper', 'Bugs', 'Coco', 'Pépère', 'Caramel', 'Noisette', 'Grisou', 
        'Flocon', 'Milo', 'Oscar', 'Simba', 'Léo', 'Rocky', 'Bandit', 'Pantoufle',
        'Chocolat', 'Éclair', 'Filou', 'Jumpy', 'Pogo', 'Rouky', 'Snoopy', 'Toby'
    ];

    // Noms pour lapines femelles
    protected static $femaleNames = [
        'Clémentine', 'Cannelle', 'Neige', 'Luna', 'Nala', 'Caline', 'Praline', 'Vanille',
        'Perle', 'Rosette', 'Framboise', 'Mirabelle', 'Pistache', 'Réglisse', 'Sucre',
        'Violette', 'Cerise', 'Doucette', 'Fleur', 'Guimauve', 'Noisette', 'Pêche', 'Truffe'
    ];

    // Noms basés sur les couleurs
    protected static $colorNames = [
        'blanc' => ['Flocon', 'Neige', 'Blanchette', 'Coton', 'Perle'],
        'noir' => ['Charbon', 'Ébène', 'Réglisse', 'Shadow', 'Onyx'],
        'gris' => ['Grisou', 'Cendre', 'Silver', 'Fumée', 'Perle'],
        'marron' => ['Chocolat', 'Noisette', 'Brownie', 'Cannelle', 'Caramel'],
        'roux' => ['Rouky', 'Roussette', 'Ginger', 'Abricot', 'Renard'],
        'fauve' => ['Simba', 'Lion', 'Fauve', 'Doré', 'Miel'],
    ];

    // Noms basés sur les races
    protected static $breedNames = [
        'Néo-Zélandais' => ['Kiwi', 'Auckland', 'Wellington'],
        'Californien' => ['Hollywood', 'Malibu', 'Sunny'],
        'Rex' => ['King', 'Queen', 'Royal', 'Velours'],
        'Géant des Flandres' => ['Titan', 'Goliath', 'Hercule', 'Géant'],
        'Nain de Hollande' => ['Tulipe', 'Amsterdam', 'Mini', 'Tiny'],
        'Angora' => ['Duvet', 'Plume', 'Soyeux', 'Doudou'],
        'Bélier' => ['Floppy', 'Dumbo', 'Oreille', 'Pendouille'],
    ];

    /**
     * Génère un nom de lapin en fonction du sexe, de la couleur et de la race
     *
     * @param string $gender Le sexe du lapin ('male' ou 'female')
     * @param string|null $color La couleur du lapin
     * @param string|null $breed La race du lapin
     * @return string Un nom généré
     */
    public static function generate(string $gender, ?string $color = null, ?string $breed = null): string
    {
        $names = [];
        
        // Ajouter les noms basés sur le sexe
        if ($gender === 'male') {
            $names = array_merge($names, self::$maleNames);
        } else {
            $names = array_merge($names, self::$femaleNames);
        }
        
        // Ajouter les noms basés sur la couleur si disponible
        if ($color && isset(self::$colorNames[strtolower($color)])) {
            $names = array_merge($names, self::$colorNames[strtolower($color)]);
        }
        
        // Ajouter les noms basés sur la race si disponible
        if ($breed) {
            foreach (self::$breedNames as $breedKey => $breedNameList) {
                if (stripos($breed, $breedKey) !== false) {
                    $names = array_merge($names, $breedNameList);
                    break;
                }
            }
        }
        
        // Mélanger le tableau pour obtenir un ordre aléatoire
        shuffle($names);
        
        // Retourner le premier nom
        return $names[0];
    }

    /**
     * Génère plusieurs suggestions de noms
     *
     * @param string $gender Le sexe du lapin ('male' ou 'female')
     * @param string|null $color La couleur du lapin
     * @param string|null $breed La race du lapin
     * @param int $count Nombre de suggestions à générer
     * @return array Liste des noms suggérés
     */
    public static function generateSuggestions(string $gender, ?string $color = null, ?string $breed = null, int $count = 5): array
    {
        $suggestions = [];
        $usedNames = [];
        
        for ($i = 0; $i < $count; $i++) {
            $name = self::generate($gender, $color, $breed);
            
            // Éviter les doublons
            while (in_array($name, $usedNames) && count($usedNames) < count(self::$maleNames) + count(self::$femaleNames)) {
                $name = self::generate($gender, $color, $breed);
            }
            
            $suggestions[] = $name;
            $usedNames[] = $name;
        }
        
        return $suggestions;
    }
}