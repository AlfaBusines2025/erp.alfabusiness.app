<?php

namespace Workdo\ToDo\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Workdo\LandingPage\Entities\MarketplacePageSetting;


class MarketPlaceSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $module = 'ToDo';

        $data['product_main_banner'] = '';
        $data['product_main_status'] = 'on';
        $data['product_main_heading'] = 'TimeTracker';
        $data['product_main_description'] = '<p>Simplify task management with Dash SaaS\'s ToDo module. Create, assign, and track tasks seamlessly, customizing stages to monitor progress effectively. From ideation to completion, streamline your workflow and ensure tasks are completed on time with intuitive tools that prioritize efficiency.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'Task Management Simplified';
        $data['dedicated_theme_description'] = '<p>Effortlessly manage task stages, meetings, and personal ToDo lists within Dash SaaS, streamlining your workflow for enhanced productivity and organization.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Effortless Task Monitoring and Analysis","dedicated_theme_section_description":"<p>Track task progress effortlessly with Dash SaaS\'s robust monitoring and analysis features. Gain insights into task completion rates, bottlenecks, and team performance with detailed analytics. Identify areas for improvement and optimize your workflow for maximum efficiency and productivity. With our comprehensive task management tools, stay ahead of deadlines and achieve your goals with confidence.</p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Calendar View for Enhanced Planning","dedicated_theme_section_description":"<p>Visualize your tasks and appointments with the calendar view feature in Dash SaaS\'s ToDo module. Plan your schedule effectively by seeing tasks alongside meetings and deadlines in a comprehensive calendar layout. With this intuitive visualization tool, manage your time efficiently and prioritize tasks based on their due dates and importance. Stay organized and on top of your commitments with Dash SaaS\'s seamless integration of task management and scheduling capabilities.</p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Personalized ToDo Lists","dedicated_theme_section_description":"<p>Stay organized and focused with personalized ToDo lists tailored to your needs. Prioritize tasks by importance, due date, or category, ensuring nothing slips through the cracks. With an intuitive interface, easily add, edit, and complete tasks on the go, keeping your workflow efficient and aligned with your goals.</p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Efficiency at Your Fingertips","dedicated_theme_section_description":"<p>Maximize productivity with Dash SaaS\'s ToDo module, designed to streamline task management and boost efficiency. With customizable task stages and intuitive interfaces, stay organized and focused on completing tasks efficiently. From individual assignments to team projects, achieve more in less time with our comprehensive tools.</p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"TimeTracker"},{"screenshots":"","screenshots_heading":"TimeTracker"},{"screenshots":"","screenshots_heading":"TimeTracker"},{"screenshots":"","screenshots_heading":"TimeTracker"},{"screenshots":"","screenshots_heading":"TimeTracker"}]';
        $data['addon_heading'] = 'What is Lorem Ipsum?';
        $data['addon_description'] = '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>';
        $data['addon_section_status'] = 'on';
        $data['whychoose_heading'] = 'What is Lorem Ipsum?';
        $data['whychoose_description'] = '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>';
        $data['pricing_plan_heading'] = 'What is Lorem Ipsum?';
        $data['pricing_plan_description'] = '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>';
        $data['pricing_plan_demo_link'] = '#';
        $data['pricing_plan_demo_button_text'] = 'View Live Demo';
        $data['pricing_plan_text'] = '{"1":{"title":"Pay-as-you-go"},"2":{"title":"Unlimited installation"},"3":{"title":"Secure cloud storage"}}';
        $data['whychoose_sections_status'] = 'on';
        $data['dedicated_theme_section_status'] = 'on';

        foreach($data as $key => $value){
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', $module)->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => $module

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
