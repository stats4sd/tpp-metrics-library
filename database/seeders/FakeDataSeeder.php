<?php

namespace Database\Seeders;

use App\Models\AltName;
use App\Models\Dimension;
use App\Models\Framework;
use App\Models\Method;
use App\Models\Metric;
use App\Models\MetricProperty;
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

        // other entities   r
        $metricUsers = MetricUser::factory()->count(10)->create();
        $scales = Scale::factory()->count(10)->create();
        $metricProperties = MetricProperty::factory()->count(10)->create();


        // create 50 metrics

        $metrics = Metric::factory()->count(50)
            // for each related entity type, get 0 - 5 random entries to link to the metric.
            ->hasAttached($this->getRandomItems($dimensions, 5), relationship: 'dimensions')
            ->hasAttached($this->getRandomItems($frameworks, 5), relationship: 'metricFrameworks')
            ->hasAttached($this->getRandomItems($methods, 5), relationship: 'metricMethods')
            ->hasAttached($this->getRandomItems($tools, 5), relationship: 'metricTools')
            ->hasAttached($this->getRandomItems($metricUsers, 5), relationship: 'metricUsers')
            ->hasAttached($this->getRandomItems($scales, 5), relationship: 'metricScales')
            ->hasAttached($this->getRandomItems($metricProperties, 5), relationship: 'metricProperties')
            ->create();

        // create alt names and assign them to random metrics

        $altNames = AltName::factory()
            ->count(40)
            ->recycle($metrics)
            ->create();


        // randomly make some metrics children of other metrics

        foreach ($metrics as $childMetric) {

            // around 20% of metrics should have a parent?
            if(random_int(1, 10) < 8) {
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

        // randomly make some dimensions children of other dimensions
        foreach ($dimensions as $childDimension) {

            // around 20% of dimensions should have a parent?
            if(random_int(1, 10) < 8) {
                continue;
            }

            $childDimension->parent_id = $dimensions
                //a dimension cannot be its own parent
                ->filter(fn ($dimension) => $dimension->id !== $childDimension->id)
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
