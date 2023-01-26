<?php

namespace Database\Seeders;

use App\Models\AltName;
use App\Models\Dimension;
use App\Models\Framework;
use App\Models\Method;
use App\Models\Metric;
use App\Models\MetricProperty;
use App\Models\MetricPropertyOption;
use App\Models\MetricUser;
use App\Models\Scale;
use App\Models\Tool;
use App\Models\Topic;
use Illuminate\Database\Seeder;

class FakeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AltName::truncate();
        Dimension::destroy(Dimension::all()->pluck('id')->toArray());
        Framework::destroy(Framework::all()->pluck('id')->toArray());
        Method::destroy(Method::all()->pluck('id')->toArray());
        MetricUser::destroy(MetricUser::all()->pluck('id')->toArray());
        Scale::destroy(Scale::all()->pluck('id')->toArray());
        Tool::destroy(Tool::all()->pluck('id')->toArray());
        Topic::destroy(Topic::all()->pluck('id')->toArray());
        MetricProperty::destroy(MetricProperty::all()->pluck('id')->toArray());
        Metric::destroy(Metric::all()->pluck('id')->toArray());


        // Topics + Dimensions
        $topics = Topic::factory()->count(5)
            ->has(Dimension::factory()->count(5), 'dimensions')
            ->create();

        $dimensions = Dimension::all();

        // Tools, Frameworks and Methods go together
        $frameworks = Framework::factory()->count(5)->create();
        $methods = Method::factory()->count(10)->create();
        $tools = Tool::factory()->count(10)->create();


        // randomly link frameworks, methods and tools...
        foreach ($tools as $tool) {

            $tool->frameworks()->sync(
                $this->getRandomItems($frameworks, 3)
            );

            $tool->methods()->sync(
                $this->getRandomItems($methods, 3)
            );
        }

        // other entities
        $metricUsers = MetricUser::factory()->count(10)->create();
        $scales = Scale::factory()->count(10)->create();

        $metricProperties = MetricProperty::factory()
            ->count(10)
            ->has(MetricPropertyOption::factory()->count(5))
            ->create();


        // create 50 metrics - via for loop instead of factory()->count() so that each metric has a different set of relations.
        for ($i = 0; $i < 50; $i++) {

            Metric::factory()
                // for each related entity type, get 0 - 5 random entries to link to the metric.
                // cannot use recycle as that only picks 1 entry (and no idea how to add to the pivot)
                ->hasAttached(
                    factory: $this->getRandomItems($dimensions, 5),
                    pivot: function ($metric) {
                        return [
                            'notes' => "{$metric->title} notes about the link to a dimension",
                        ];
                    },
                    relationship: 'dimensions')
                ->hasAttached(
                    factory: $this->getRandomItems($frameworks, 5),
                    pivot: function ($metric) {
                        return [
                            'notes' => "{$metric->title} notes about metricFrameworks relationship"
                        ];
                    }, relationship: 'metricFrameworks')
                ->hasAttached(
                    factory: $this->getRandomItems($methods, 5),
                    pivot: function ($metric) {
                        return [
                            'notes' => "{$metric->title} notes about metricMethods relationship"
                        ];
                    }, relationship: 'metricMethods')
                ->hasAttached(
                    factory: $this->getRandomItems($tools, 5),
                    pivot: function ($metric) {
                        return [
                            'notes' => "{$metric->title} notes about metricTools relationship"
                        ];
                    }, relationship: 'metricTools')
                ->hasAttached(
                    factory: $this->getRandomItems($metricUsers, 5),
                    pivot: function ($metric) {
                        return [
                            'notes' => "{$metric->title} notes about metricUsers relationship"
                        ];
                    }, relationship: 'metricUsers')
                ->hasAttached(
                    factory: $this->getRandomItems($scales, 5),
                    pivot: function ($metric) {
                        return [
                            'notes' => "{$metric->title} notes about metricScales relationship"
                        ];
                    }, relationship: 'metricScales')
                ->hasAttached(
                    factory: $this->getRandomItems($metricProperties, 5),
                    pivot: function ($metric) {
                        return [
                            'notes' => "{$metric->title} notes about metricProperties relationship"
                        ];
                    }, relationship: 'metricProperties')
                ->create();
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
                ->filter(fn($metric) => $metric->id !== $childMetric->id)
                ->shuffle()
                ->take(1)
                ->pluck('id')
                ->first();

            $childMetric->save();
        }

        // randomly make some dimensions children of other dimensions
        foreach ($dimensions as $childDimension) {

            // around 20% of dimensions should have a parent?
            if (random_int(1, 10) < 8) {
                continue;
            }

            $childDimension->parent_id = $dimensions
                //a dimension cannot be its own parent
                ->filter(fn($dimension) => $dimension->id !== $childDimension->id)
                ->shuffle()
                ->take(1)
                ->pluck('id')
                ->first();

            $childDimension->save();
        }

    }

    /** Gets random items from the collection as an array of IDs (for syncing via many-many relationships) */
    public function getRandomItems($collection, $max)
    {
        $count = random_int(0, $max);

        return $collection->shuffle()->take($count)->pluck('id')->toArray();
    }
}
