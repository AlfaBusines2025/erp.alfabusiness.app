<?php

namespace Workdo\DairyCattleManagement\Database\Seeders;

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
        $module = 'DairyCattleManagement';


        $data['product_main_banner'] = '';
        $data['product_main_status'] = 'on';
        $data['product_main_heading'] = 'DairyCattleManagement';
        $data['product_main_description'] = '<p>Dairy and Cattle Management module is your comprehensive solution for dairy cattle management. Our platform is designed to simplify the complexities of managing a dairy farm, offering seamless integration for tasks such as animal health monitoring, breeding management, weight tracking, daily milk yield recording, and milk inventory management. With Dash SaaS, you can efficiently oversee all aspects of your operation, ensuring the well-being of your herd while maximizing productivity.
        </p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'Dairy Management Simplified: Dash SaaS';
        $data['dedicated_theme_description'] = '<p>Dash SaaS streamlines dairy farm operations with intuitive tools for tracking animal health, milk production, and inventory, enabling farmers to optimize efficiency and profitability effortlessly.
        </p>';
        $data['dedicated_theme_sections'] = '[
            {
                "dedicated_theme_section_image": "",
                "dedicated_theme_section_heading": "Animal Management",
                "dedicated_theme_section_description": "<p>This module provides a user-friendly interface for managing your entire herd. Each animal has a detailed profile where you can record essential information such as health records, breeding history, and weight measurements. Our system also simplifies identification with unique animal tags and visual profiles, allowing you to quickly access the information you need for each individual animal.<\/p>",
                "dedicated_theme_section_cards": {
                    "1": {
                        "title": null,
                        "description": null
                    }
                }
            },
            {
                "dedicated_theme_section_image": "",
                "dedicated_theme_section_heading": "Health Monitoring and Breeding Management",
                "dedicated_theme_section_description": "<p>Stay proactive in maintaining the health of your herd with this modules real-time monitoring and alert system. Record veterinary visits, medications, vaccinations, and other health-related data to ensure timely intervention when needed. Additionally, our platform facilitates efficient breeding management, allowing you to plan and track breeding cycles, record mating events, and analyze breeding performance over time. With these tools at your disposal, you can optimize breeding practices and enhance the genetic quality of your herd.<\/p>",
                "dedicated_theme_section_cards": {
                    "1": {
                        "title": null,
                        "description": null
                    }
                }
            },
            {
                "dedicated_theme_section_image": "",
                "dedicated_theme_section_heading": "Milk Production Tracking",
                "dedicated_theme_section_description": "<p>Accurately track daily milk yields with Dash SaaS intuitive recording system. Generate milk production sheets for individual animals or the entire herd effortlessly, enabling you to monitor milk output trends and identify any deviations promptly. By staying informed about milk production levels, you can make informed decisions to maximize efficiency and profitability on your dairy farm.<\/p>",
                "dedicated_theme_section_cards": {
                    "1": {
                        "title": null,
                        "description": null
                    }
                }
            },
            {
                "dedicated_theme_section_image":"",
                "dedicated_theme_section_heading":"Milk Inventory Management",
                "dedicated_theme_section_description":"<p>Maintain precise control over your milk inventory with Dash SaaS. Our platform allows you to track milk production and usage for various purposes, including sales, processing, and consumption. Detailed reports generated by Dash SaaS facilitate inventory reconciliation and management, ensuring that you always have an accurate understanding of your milk inventory levels. With Dash SaaS, you can streamline your dairy operation, improve decision-making, and achieve greater success in the dairy industry.<\/p>",
                "dedicated_theme_section_cards":{
                    "1":{
                        "title":null,
                        "description":null
                    }
                }
            }
        ]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"DairyCattleManagement"},{"screenshots":"","screenshots_heading":"DairyCattleManagement"},{"screenshots":"","screenshots_heading":"DairyCattleManagement"},{"screenshots":"","screenshots_heading":"DairyCattleManagement"},{"screenshots":"","screenshots_heading":"DairyCattleManagement"}]';
        $data['addon_heading'] = '<h2>Why choose dedicated modules<b> for Your Business?</b></h2>';
        $data['addon_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['addon_section_status'] = 'on';
        $data['whychoose_heading'] = 'Why choose dedicated modulesfor Your Business?';
        $data['whychoose_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['pricing_plan_heading'] = 'Empower Your Workforce with DASH';
        $data['pricing_plan_description'] = '<p>Access over Premium Add-ons for Accounting, HR, Payments, Leads, Communication, Management, and more, all in one place!</p>';
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
