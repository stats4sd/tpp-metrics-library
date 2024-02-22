<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SdgsSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('sdgs')->delete();

        \DB::table('sdgs')->insert(array(
            0 =>
            array(
                'id' => 1,
                'name' => ' No Poverty',
                'definition' => 'End poverty in all its forms everywhere',
                'notes' => NULL,
                'created_at' => '2024-02-01 10:28:07',
                'updated_at' => '2024-02-01 10:28:07',
            ),
            1 =>
            array(
                'id' => 2,
                'name' => ' Zero Hunger',
                'definition' => 'End hunger, achieve food security and improved nutrition and promote sustainable agriculture.',
                'notes' => NULL,
                'created_at' => '2024-02-01 10:28:07',
                'updated_at' => '2024-02-01 10:28:07',
            ),
            2 =>
            array(
                'id' => 3,
                'name' => ' Good Health and Well-being',
                'definition' => 'Ensure healthy lives and promote well-being for all at all ages.',
                'notes' => NULL,
                'created_at' => '2024-02-01 10:28:07',
                'updated_at' => '2024-02-01 10:28:07',
            ),
            3 =>
            array(
                'id' => 4,
                'name' => ' Quality Education',
                'definition' => 'Ensure inclusive and equitable quality education and promote lifelong learning opportunities for all.',
                'notes' => NULL,
                'created_at' => '2024-02-01 10:28:07',
                'updated_at' => '2024-02-01 10:28:07',
            ),
            4 =>
            array(
                'id' => 5,
                'name' => ' Gender Equality',
                'definition' => 'Achieve gender equality and empower all women and girls.',
                'notes' => NULL,
                'created_at' => '2024-02-01 10:28:07',
                'updated_at' => '2024-02-01 10:28:07',
            ),
            5 =>
            array(
                'id' => 6,
                'name' => ' Clean Water and Sanitation',
                'definition' => 'Ensure availability and sustainable management of water and sanitation for all.',
                'notes' => NULL,
                'created_at' => '2024-02-01 10:28:07',
                'updated_at' => '2024-02-01 10:28:07',
            ),
            6 =>
            array(
                'id' => 7,
                'name' => ' Affordable and Clean Energy',
                'definition' => 'Ensure access to affordable, reliable, sustainable and modern energy for all.',
                'notes' => NULL,
                'created_at' => '2024-02-01 10:28:07',
                'updated_at' => '2024-02-01 10:28:07',
            ),
            7 =>
            array(
                'id' => 8,
                'name' => ' Decent Work and Economic Growth',
                'definition' => 'Promote sustained, inclusive and sustainable economic growth, full and productive employment and decent work for all.',
                'notes' => NULL,
                'created_at' => '2024-02-01 10:28:07',
                'updated_at' => '2024-02-01 10:28:07',
            ),
            8 =>
            array(
                'id' => 9,
                'name' => ' Industry,  Innovation and Infrastructure',
                'definition' => 'Build resilient infrastructure, promote inclusive and sustainable industrialization and foster innovation.',
                'notes' => NULL,
                'created_at' => '2024-02-01 10:28:07',
                'updated_at' => '2024-02-01 10:28:07',
            ),
            9 =>
            array(
                'id' => 10,
                'name' => ' Reduced Inequality',
                'definition' => 'Reduce inequality within and among countries.',
                'notes' => NULL,
                'created_at' => '2024-02-01 10:28:07',
                'updated_at' => '2024-02-01 10:28:07',
            ),
            10 =>
            array(
                'id' => 11,
                'name' => ' Sustainable Cities and Communities',
                'definition' => 'Make cities and human settlements inclusive, safe, resilient and sustainable.',
                'notes' => NULL,
                'created_at' => '2024-02-01 10:28:07',
                'updated_at' => '2024-02-01 10:28:07',
            ),
            11 =>
            array(
                'id' => 12,
                'name' => ' Responsible Consumption and Production',
                'definition' => 'Ensure sustainable consumption and production patterns.',
                'notes' => NULL,
                'created_at' => '2024-02-01 10:28:07',
                'updated_at' => '2024-02-01 10:28:07',
            ),
            12 =>
            array(
                'id' => 13,
                'name' => ' Climate Action',
                'definition' => 'Take urgent action to combat climate change and its impacts.',
                'notes' => NULL,
                'created_at' => '2024-02-01 10:28:07',
                'updated_at' => '2024-02-01 10:28:07',
            ),
            13 =>
            array(
                'id' => 14,
                'name' => ' Life Below Water',
                'definition' => 'Conserve and sustainably use the oceans, seas and marine resources for sustainable development.',
                'notes' => NULL,
                'created_at' => '2024-02-01 10:28:07',
                'updated_at' => '2024-02-01 10:28:07',
            ),
            14 =>
            array(
                'id' => 15,
                'name' => ' Life on Land',
                'definition' => 'Protect restore and promote sustainable use of terrestrial ecosystems, sustainably manage forests, combat desertification, and halt and reverse land degradation and halt biodiversity loss.',
                'notes' => NULL,
                'created_at' => '2024-02-01 10:28:07',
                'updated_at' => '2024-02-01 10:28:07',
            ),
            15 =>
            array(
                'id' => 16,
                'name' => ' Peace and Justice Strong Institutions',
                'definition' => 'Promote peaceful and inclusive societies for sustainable development, provide access to justice for all and build effective, accountable and inclusive institutions at all levels.',
                'notes' => NULL,
                'created_at' => '2024-02-01 10:28:07',
                'updated_at' => '2024-02-01 10:28:07',
            ),
            16 =>
            array(
                'id' => 17,
                'name' => ' Partnerships to achieve the Goal',
                'definition' => 'Strengthen the means of implementation and revitalize the global partnership for sustainable development.',
                'notes' => NULL,
                'created_at' => '2024-02-01 10:28:07',
                'updated_at' => '2024-02-01 10:28:07',
            ),
        ));
    }
}
