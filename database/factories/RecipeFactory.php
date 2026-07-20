<?php

namespace Database\Factories;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Curated recipe names with their instructions.
     *
     * @var array<string, string>
     */
    protected static array $recipes = [
        'Sarma' => 'Prelijte kupusove listove kipućom vodom. Pomešajte mleveno meso, pirinač, luk i začine, umotajte u listove kupusa i ređajte u šerpu. Kuvajte na laganoj vatri oko 2 sata.',
        'Musaka' => 'Krompir isecite na kolutove i propržite. Napravite fil od mlevenog mesa sa lukom i paradajzom. Ređajte slojeve krompira i mesa, prelijte preplivom od jaja i mleka, pa pecite u rerni 45 minuta.',
        'Pasulj' => 'Pasulj prokuvajte i ocedite prvu vodu. Dodajte suvo meso, luk, šargarepu i papriku. Kuvajte na laganoj vatri dok pasulj ne omekša, začinite po ukusu.',
        'Karađorđeva šnicla' => 'Meso istucite, napunite kajmakom i uvijte u rolat. Uvaljajte u brašno, jaje i prezle, pa pržite u dubokom ulju dok ne porumeni.',
        'Đuveč' => 'Isecite povrće na kockice. Propržite luk i papriku, dodajte pirinač, paradajz i ostalo povrće. Dinstajte dok pirinač ne omekša.',
        'Punjene paprike' => 'Paprike očistite od semenki. Napunite ih smesom od mlevenog mesa i pirinča. Ređajte u šerpu, prelijte paradajz sosom i kuvajte dok meso ne bude gotovo.',
        'Riblja čorba' => 'Ribu i povrće prokuvajte u vodi sa začinima. Kuvajte na laganoj vatri oko sat vremena, povremeno skidajući penu. Začinite alevom paprikom pred kraj.',
        'Palačinke' => 'Umutite jaja, mleko, brašno i malo soli u glatko testo. Pecite tanke palačinke na vrućem tiganju sa obe strane. Poslužite sa marmeladom ili čokoladom.',
        'Pileća supa' => 'Piletinu i povrće prokuvajte u vodi. Kuvajte na laganoj vatri dok meso ne omekša, skidajte penu povremeno. Na kraju dodajte rezance.',
        'Gulaš' => 'Meso isecite na kockice i propržite sa lukom. Dodajte alevu papriku, paradajz i vodu. Krčkajte na laganoj vatri dok meso ne omekša.',
        'Ćevapi' => 'Mleveno meso izmešajte sa začinima i sodom bikarbonom. Oblikujte male ćevapčiće i ostavite da odstoje. Pecite na roštilju dok ne dobiju zlatnu koru.',
        'Pljeskavica' => 'Mleveno meso izmešajte sa lukom, začinima i sodom bikarbonom. Oblikujte pljeskavicu i ostavite da odstoji u frižideru. Pecite na roštilju sa obe strane.',
        'Ajvar' => 'Paprike i plavi patlidžan ispecite na roštilju ili u rerni dok koža ne pocrni. Ogulite ih i sameljite, pa dinstajte sa uljem dok ne dobije gustu teksturu.',
        'Kupus salata' => 'Kupus naribajte na tanke listiće i posolite. Ostavite da otpusti vodu, a zatim ocedite. Začinite uljem, sirćetom i po želji šargarepom.',
        'Šopska salata' => 'Isecite paradajz, krastavac i papriku na kockice. Izmešajte povrće, dodajte so i ulje. Pospite rendanim sirom na kraju.',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(array_keys(self::$recipes));

        return [
            'name' => $name,
            'instructions' => self::$recipes[$name],
            'image' => null,
            'prep_time' => fake()->numberBetween(15, 90),
        ];
    }
}
