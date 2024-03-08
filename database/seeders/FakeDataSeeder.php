<?php

namespace Database\Seeders;

use App\Models\AltName;
use App\Models\Developer;
use App\Models\Dimension;
use App\Models\Framework;
use App\Models\Geography;
use App\Models\Metric;
use App\Models\MetricUser;
use App\Models\Scale;
use App\Models\Tool;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Seeder;

class FakeDataSeeder extends Seeder
{

    protected Generator $faker;

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->faker = Container::getInstance()->make(Generator::class);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AltName::truncate();
        Framework::destroy(Framework::all()->pluck('id')->toArray());
        MetricUser::destroy(MetricUser::all()->pluck('id')->toArray());
        Scale::destroy(Scale::all()->pluck('id')->toArray());
        Tool::destroy(Tool::all()->pluck('id')->toArray());
        Metric::destroy(Metric::all()->pluck('id')->toArray());
        // SubDimension::destroy(SubDimension::all()->pluck('id')->toArray());

        // foreach (Dimension::all() as $dimension) {
        //     $number = random_int(1, 4);
        //     SubDimension::factory()->count($number)->create(['dimension_id' => $dimension->id]);
        // }

        // Tools, Frameworks and Methods go together
        $frameworks = Framework::factory()->count(5)->create();
        $tools = Tool::factory()->count(10)->create();


        // randomly link frameworks, methods and tools...
        foreach ($tools as $tool) {

            $sync = $this->getRandomItems($frameworks, 3);
            $syncWithNotes = [];
            foreach ($sync as $item) {
                $syncWithNotes[$item] = ['notes' => $this->faker->paragraph()];
            }

            $tool->frameworks()->sync($syncWithNotes);
        }


        // other entities
        $metricUsers = MetricUser::factory()->count(10)->create();
        $scales = Scale::factory()->count(10)->create();
        $developers = Developer::factory()->count(50)->create();
        $geographies = Geography::factory()->count(50)->create();


        $dimensions = Dimension::all();

        // create 50 metrics - via for loop instead of factory()->count() so that each metric has a different set of relations.
        for ($i = 0; $i < 50; $i++) {

            Metric::factory()
                ->hasAttached(
                    factory: $this->getRandomItems($frameworks, 5),
                    pivot: function ($metric) {
                        return [
                            'notes' => "{$metric->title} notes about metricFrameworks relationship"
                        ];
                    },
                    relationship: 'frameworks'
                )
                ->hasAttached(
                    factory: $this->getRandomItems($tools, 5),
                    pivot: function ($metric) {
                        return [
                            'notes' => "{$metric->title} notes about metricTools relationship"
                        ];
                    },
                    relationship: 'tools'
                )
                ->hasAttached(
                    factory: $this->getRandomItems($metricUsers, 5),
                    pivot: function ($metric) {
                        return [
                            'notes' => "{$metric->title} notes about collectors of the metric"
                        ];
                    },
                    relationship: 'collectors'
                )
                ->hasAttached(
                    factory: $this->getRandomItems($metricUsers, 5),
                    pivot: function ($metric) {
                        return [
                            'notes' => "{$metric->title} notes about those who use this metric for decision making"
                        ];
                    },
                    relationship: 'decisionMakers'
                )
                ->hasAttached(
                    factory: $this->getRandomItems($metricUsers, 5),
                    pivot: function ($metric) {
                        return [
                            'notes' => "{$metric->title} notes about those impacted by this metric"
                        ];
                    },
                    relationship: 'impactedBy'
                )
                ->hasAttached(
                    factory: $this->getRandomItems($scales, 5),
                    pivot: function ($metric) {
                        return [
                            'notes' => "{$metric->title} notes about scaleDecision relationship"
                        ];
                    },
                    relationship: 'scaleDecision'
                )
                ->hasAttached(
                    factory: $this->getRandomItems($scales, 5),
                    pivot: function ($metric) {
                        return [
                            'notes' => "{$metric->title} notes about scaleMeasurement relationship"
                        ];
                    },
                    relationship: 'scaleMeasurement'
                )
                ->hasAttached(
                    factory: $this->getRandomItems($scales, 5),
                    pivot: function ($metric) {
                        return [
                            'notes' => "{$metric->title} notes about scaleReporting relationship"
                        ];
                    },
                    relationship: 'scaleReporting'
                )
                ->hasAttached(
                    factory: $this->getRandomItems($geographies, 5),
                    pivot: function ($metric) {
                        return [
                            'notes' => "{$metric->title} notes about geographies"
                        ];
                    },
                    relationship: 'geographies'
                )
                ->hasDataSources(3, ['reference_type' => 'data source'])
                ->hasComputationGuidance(2, ['reference_type' => 'computation guidance'])
                ->hasReferences(3, ['reference_type' => 'reference'])
                ->create(['developer_id' => $developers->shuffle()->first()->id]);
        }

        // create alt names and assign them to random metrics
        $metrics = Metric::all();

        $altNames = AltName::factory()
            ->count(40)
            ->recycle($metrics)
            ->create();


        // randomly make some metrics children of other metrics

        foreach ($metrics as $childMetric) {

            // around 20% of metrics should have a parent?
            if (random_int(1, 10) < 8) {
                continue;
            }

            $childMetric->parent_id = $metrics
                //a metric cannot be its own parent
                ->filter(fn ($metric) => $metric->id !== $childMetric->id)
                ->shuffle()
                ->take(1)
                ->pluck('id')
                ->first();

            $childMetric->save();
        }
    }

    /** Gets random items from the collection as an array of IDs (for syncing via many-many relationships) */
    public
    function getRandomItems($collection, $max): array
    {
        $count = random_int(0, $max);

        return $collection->shuffle()->take($count)->pluck('id')->toArray();
    }
}
