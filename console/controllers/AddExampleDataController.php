<?php

namespace console\controllers;

use common\models\Fnord;
use common\models\Foo;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Controller for adding example data to the database.
 *
 * Used in Docker Stage Environment to initialize example tables with some random data.
 */
class AddExampleDataController extends Controller
{
    /**
     * Number of Fnords to create
     *
     * @var int
     */
    public int $countFnords = 42;

    /**
     * Maximum number of Foos per Fnord
     *
     * @var int
     */
    public int $maxFoosPerFnord = 5;

    /**
     * {@inheritdoc}
     */
    public function options($actionID): array
    {
        return array_merge(parent::options($actionID), [
            'countFnords',
            'maxFoosPerFnord',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function optionAliases(): array
    {
        return array_merge(parent::optionAliases(), [
            'c' => 'countFnords',
            'm' => 'maxFoosPerFnord',
        ]);
    }

    /**
     * Add example data
     *
     * @return int Exit code
     */
    public function actionIndex(): int
    {
        echo 'Adding sample stage/dev data...' . PHP_EOL;


        // Add your own code here
        // ...


        // Create sample data for "fnord" and "foo" models
        echo "Creating {$this->countFnords} Fnords with up to {$this->maxFoosPerFnord} Foos each." . PHP_EOL . PHP_EOL;

        $discordianTerms = [
            'Eris', 'Kallisti', 'Sacred Chao', 'Hodge', 'Podge',
            'Pineal Gland', 'Golden Apple', 'Law of Fives', 'Operation Mindfuck',
            'Greyface', 'Mal-2', 'Omar Khayyam Ravenhurst', 'Malaclypse the Younger',
            'Principia Discordia', 'Pentabarf', 'Curse of Greyface', 'Erisian',
            'Paratheoanametamystikhood', 'Pope', 'Episkopos', 'Five Fingered Hand of Eris',
            'Aneristic Principle', 'Eristic Principle', 'Mu', 'Hodge-Podge Transform',
            'St. Hung Mung', 'Sri Syadasti', 'Bavarian Illuminati', 'Legion of Dynamic Discord',
            'Paratheo-Anametamystikhood of Eris Esoteric', 'Season of Chaos'
        ];

        $bofhExcuses = [
            'cosmic rays', 'solar flares', 'electromagnetic interference',
            'quantum tunneling', 'butterfly effect', 'chaotic resonance',
            'TCP/IP oversight', 'routing problems', 'excessive collisions',
            'IRQ conflict', 'defunct processes', 'zombie apocalypse in kernel space',
            'temporal anomaly', 'reality distortion field', 'subspace interference',
            'parallel universe bleed-through', 'Heisenberg compensation failure',
            'entropy reversal', 'time dilation', 'magnetic monopole detected'
        ];

        $discordianPhrases = [
            'Hail Eris!', 'Fnord', 'May Eris grant you Discord',
            'There is no Goddess but Goddess', 'Consult your pineal gland',
            'The Golden Apple of Discord', 'A Pope is someone who is not under the authority of the authorities',
            'All statements are true in some sense', 'Think for yourself, schmuck!',
            'It is a law of nature that nobody can count higher than five',
            'We Discordians must stick apart', 'If you think the Illuminati are a conspiracy, you are wrong',
            'Slack is the most important thing'
        ];

        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $fnordsCreated = 0;
            $foosCreated = 0;

            // Create Fnords with random data
            for ($i = 0; $i < $this->countFnords; $i++) {
                $fnord = new Fnord();

                // Mix Discordian terms with BOFH excuses for variety
                if ($i % 2 === 0) {
                    $fnord->bar = $discordianTerms[array_rand($discordianTerms)];
                    $fnord->baz = $discordianPhrases[array_rand($discordianPhrases)];
                } else {
                    $fnord->bar = $bofhExcuses[array_rand($bofhExcuses)];
                    $fnord->baz = $discordianTerms[array_rand($discordianTerms)];
                }

                if (!$fnord->save()) {
                    throw new \Exception('Failed to save Fnord #$i: ' . json_encode($fnord->errors));
                }

                $fnordsCreated++;

                // Create 0-N Foos for each Fnord
                $fooCount = rand(0, $this->maxFoosPerFnord);

                for ($j = 0; $j < $fooCount; $j++) {
                    $foo = new Foo();
                    $foo->id_fnord = $fnord->id_fnord;

                    // Select random number
                    $numbers = [1, 2, 3, 4, 5, 8, 16, 17, 23, 32, 42, 46, 64, 73, 93, 108, 128, 512, 555, 1024, 2305];
                    $foo->foo_value = $numbers[array_rand($numbers)];

                    if (!$foo->save()) {
                        throw new \Exception("Failed to save Foo for Fnord #{$fnord->id_fnord}: " . json_encode($foo->errors));
                    }

                    $foosCreated++;
                }

                echo "Created Fnord #{$fnord->id_fnord} with {$fooCount} Foo(s)" . PHP_EOL;
            }

            $transaction->commit();

            echo PHP_EOL;
            echo '=== Success! ===' . PHP_EOL;
            echo "Created {$fnordsCreated} Fnords and {$foosCreated} Foos" . PHP_EOL;

            return ExitCode::OK;
        } catch (\Exception $e) {
            $transaction->rollBack();
            echo 'Error: ' . $e->getMessage() . PHP_EOL;
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
}
