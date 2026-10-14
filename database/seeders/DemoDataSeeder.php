<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Complaint;
use App\Models\Department;
use App\Models\EmployeeFeedback;
use App\Models\Feedback;
use App\Models\FeedbackTicket;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // BusinessType entities removed from demo seeder

        $solsona = Branch::firstOrCreate(
            ['code' => 'SLS-001'],
            [
                'name' => 'Solsona Branch',
                'slug' => 'solsona-branch',
                // 'business_type_id' => removed,
                'address' => 'Brgy. San Roque, Solsona, Ilocos Norte',
                'contact_number' => '+63 912 345 6789',
                'email' => 'solsona@gledco.test',
                'qr_token' => Str::upper(Str::random(24)),
                'status' => 'active',
                'description' => 'Fuel station branch serving Solsona and nearby towns.',
            ]
        );

        $solaneLaoag = Branch::firstOrCreate(
            ['code' => 'SLN-LAO'],
            [
                'name' => 'Solane Laoag Branch',
            // Business types removed from demo data
            ]
        );

        $solaneGabu = Branch::firstOrCreate(
            ['code' => 'SLN-GAB'],
            [
                'name' => 'Solane Gabu Branch',
                'slug' => 'solane-gabu-branch',
                // 'business_type_id' => removed,
                'address' => 'Gabu, Ilocos Norte',
                'contact_number' => '+63 920 000 0003',
                'email' => 'solane-gabu@gledco.test',
                'qr_token' => Str::upper(Str::random(24)),
                'status' => 'active',
                'description' => 'Solane branch in Gabu.',
            ]
        );

        $solaneNalbo = Branch::firstOrCreate(
            ['code' => 'SLN-NAL'],
            [
                'name' => 'Solane Nalbo Branch',
                'slug' => 'solane-nalbo-branch',
                // 'business_type_id' => removed,
                'address' => 'Nalbo, Ilocos Norte',
                'contact_number' => '+63 920 000 0004',
                'email' => 'solane-nalbo@gledco.test',
                'qr_token' => Str::upper(Str::random(24)),
                'status' => 'active',
                'description' => 'Solane branch in Nalbo.',
            ]
        );

        $solaneBacarra = Branch::firstOrCreate(
            ['code' => 'SLN-BAC'],
            [
                'name' => 'Solane Bacarra Branch',
                'slug' => 'solane-bacarra-branch',
                // 'business_type_id' => removed,
                'address' => 'Bacarra, Ilocos Norte',
                'contact_number' => '+63 920 000 0005',
                'email' => 'solane-bacarra@gledco.test',
                'qr_token' => Str::upper(Str::random(24)),
                'status' => 'active',
                'description' => 'Solane branch in Bacarra.',
            ]
        );

        $enterprisesBatac = Branch::firstOrCreate(
            ['code' => 'ENT-BAT'],
            [
                'name' => 'Enterprises Batac Branch',
                'slug' => 'enterprises-batac-branch',
                // 'business_type_id' => removed,
                'address' => 'Batac, Ilocos Norte',
                'contact_number' => '+63 920 000 0011',
                'email' => 'enterprises-batac@gledco.test',
                'qr_token' => Str::upper(Str::random(24)),
                'status' => 'active',
                'description' => 'Enterprise branch in Batac.',
            ]
        );

        $enterprisesLaoag = Branch::firstOrCreate(
            ['code' => 'ENT-LAO'],
            [
                'name' => 'Enterprises Laoag Branch',
                'slug' => 'enterprises-laoag-branch',
                // 'business_type_id' => removed,
                'address' => 'Laoag City, Ilocos Norte',
                'contact_number' => '+63 920 000 0012',
                'email' => 'enterprises-laoag@gledco.test',
                'qr_token' => Str::upper(Str::random(24)),
                'status' => 'active',
                'description' => 'Enterprise branch in Laoag.',
            ]
        );

        $enterprisesSolsona = Branch::firstOrCreate(
            ['code' => 'ENT-SLS'],
            [
                'name' => 'Enterprises Solsona Branch',
                'slug' => 'enterprises-solsona-branch',
                // 'business_type_id' => removed,
                'address' => 'Solsona, Ilocos Norte',
                'contact_number' => '+63 920 000 0013',
                'email' => 'enterprises-solsona@gledco.test',
                'qr_token' => Str::upper(Str::random(24)),
                'status' => 'active',
                'description' => 'Enterprise branch in Solsona.',
            ]
        );

        $caltexLaoag = Branch::firstOrCreate(
            ['code' => 'CLX-LAO'],
            [
                'name' => 'Caltex Brgy 2 Laoag Branch',
                'slug' => 'caltex-brgy-2-laoag-branch',
                // 'business_type_id' => removed,
                'address' => 'Brgy. 2, Laoag City, Ilocos Norte',
                'contact_number' => '+63 920 000 0021',
                'email' => 'caltex-laoag@gledco.test',
                'qr_token' => Str::upper(Str::random(24)),
                'status' => 'active',
                'description' => 'Caltex branch in Brgy 2, Laoag.',
            ]
        );

        $caltexNwu = Branch::firstOrCreate(
            ['code' => 'CLX-NWU'],
            [
                'name' => 'Caltex NWU Branch',
                'slug' => 'caltex-nwu-branch',
                // 'business_type_id' => removed,
                'address' => 'NWU Campus, Laoag City, Ilocos Norte',
                'contact_number' => '+63 920 000 0022',
                'email' => 'caltex-nwu@gledco.test',
                'qr_token' => Str::upper(Str::random(24)),
                'status' => 'active',
                'description' => 'Caltex branch serving NWU area.',
            ]
        );

        $caltexSanNicolas = Branch::firstOrCreate(
            ['code' => 'CLX-SAN'],
            [
                'name' => 'Caltex San Nicolas Branch',
                'slug' => 'caltex-san-nicolas-branch',
                // 'business_type_id' => removed,
                'address' => 'San Nicolas, Ilocos Norte',
                'contact_number' => '+63 920 000 0023',
                'email' => 'caltex-san-nicolas@gledco.test',
                'qr_token' => Str::upper(Str::random(24)),
                'status' => 'active',
                'description' => 'Caltex branch in San Nicolas.',
            ]
        );

        $batac = Branch::firstOrCreate(
            ['code' => 'BAT-002'],
            [
                'name' => 'Batac Branch',
                'slug' => 'batac-branch',
                // 'business_type_id' => removed,
                'address' => 'Poblacion Norte, Batac, Ilocos Norte',
                'contact_number' => '+63 913 555 1234',
                'email' => 'batac@gledco.test',
                'qr_token' => Str::upper(Str::random(24)),
                'status' => 'active',
                'description' => 'LPG branch for residential and commercial refills.',
            ]
        );

        $laoag = Branch::firstOrCreate(
            ['code' => 'LAO-003'],
            [
                'name' => 'Laoag Branch',
                'slug' => 'laoag-branch',
                // 'business_type_id' => removed,
                'address' => 'Gen. Luna St., Laoag City, Ilocos Norte',
                'contact_number' => '+63 914 999 2345',
                'email' => 'laoag@gledco.test',
                'qr_token' => Str::upper(Str::random(24)),
                'status' => 'active',
                'description' => 'Retail branch offering consumer products and services.',
            ]
        );

        $manila = Branch::firstOrCreate(
            ['code' => 'MNL-HQ'],
            ['name' => 'Manila HQ', 'slug' => 'manila-hq', 'description' => 'Head office branch', 'status' => 'active', 'qr_token' => Str::upper(Str::random(24))]
        );

        $cebu = Branch::firstOrCreate(
            ['code' => 'CEB-OPS'],
            ['name' => 'Cebu Operations', 'slug' => 'cebu-operations', 'description' => 'Regional operations branch', 'status' => 'active', 'qr_token' => Str::upper(Str::random(24))]
        );

        FeedbackTicket::firstOrCreate(
            ['reference_no' => 'TCK-000001'],
            [
                'branch_id' => $cebu->id,
                'satisfaction' => 'good',
                'category' => 'service',
                'comment' => 'The staff was polite and the line moved quickly. Thank you!',
                'customer_name' => 'Guest User',
                'customer_email' => 'guest@gledco.test',
                'status' => 'pending',
                'is_anonymous' => false,
                'source' => 'qr',
            ]
        );

        FeedbackTicket::firstOrCreate(
            ['reference_no' => 'TCK-000002'],
            [
                'branch_id' => $solsona->id,
                'satisfaction' => 'neutral',
                'category' => 'product',
                'comment' => 'The price board was unclear. Please show regular and premium prices separately.',
                'customer_phone' => '+63 912 000 0000',
                'status' => 'under_review',
                'is_anonymous' => true,
                'source' => 'qr',
            ]
        );

        $diesel = Product::firstOrCreate(
            ['name' => 'Diesel'],
            ['description' => 'Diesel fuel for commercial and consumer vehicles.', 'status' => 'active', 'icon' => 'droplet']
        );

        $gasoline = Product::firstOrCreate(
            ['name' => 'Gasoline'],
            ['description' => 'Premium gasoline for passenger cars and motorbikes.', 'status' => 'active', 'icon' => 'fuel']
        );

        $lpgProduct = Product::firstOrCreate(
            ['name' => 'LPG'],
            ['description' => 'Liquefied petroleum gas for cooking and heating.', 'status' => 'active', 'icon' => 'flame']
        );

        $lubricants = Product::firstOrCreate(
            ['name' => 'Lubricants'],
            ['description' => 'Lubricants and vehicle care products.', 'status' => 'active', 'icon' => 'settings']
        );

        $solsona->products()->syncWithoutDetaching([
            $diesel->id => ['status' => 'active'],
            $gasoline->id => ['status' => 'active'],
        ]);

        $batac->products()->syncWithoutDetaching([
            $lpgProduct->id => ['status' => 'active'],
        ]);

        $laoag->products()->syncWithoutDetaching([
            $lubricants->id => ['status' => 'active'],
        ]);

        $cebu->products()->syncWithoutDetaching([
            $diesel->id => ['status' => 'active'],
            $lubricants->id => ['status' => 'inactive'],
        ]);

        Department::firstOrCreate(
            ['branch_id' => $solsona->id, 'code' => 'FUEL'],
            ['name' => 'Fuel Station Department', 'description' => 'Handles forecourt service, fueling support, and station concerns.', 'status' => 'active']
        );

        Department::firstOrCreate(
            ['branch_id' => $solsona->id, 'code' => 'LPG'],
            ['name' => 'LPG Department', 'description' => 'Handles LPG refill and cylinder-related concerns.', 'status' => 'active']
        );

        Department::firstOrCreate(
            ['branch_id' => $batac->id, 'code' => 'RETAIL'],
            ['name' => 'Retail Department', 'description' => 'Handles retail inventory and checkout concerns.', 'status' => 'active']
        );

        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@gledco.test'],
            ['name' => 'GLEDCO Super Admin', 'password' => bcrypt('password'), 'branch_id' => $manila->id, 'job_title' => 'System Owner', 'email_verified_at' => now()]
        );
        $superAdmin->forceFill(['email_verified_at' => $superAdmin->email_verified_at ?? now()])->save();
        $superAdmin->syncRoles(['Super Admin']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@gledco.test'],
            ['name' => 'GLEDCO Admin', 'password' => bcrypt('password'), 'branch_id' => $manila->id, 'job_title' => 'Operations Admin', 'email_verified_at' => now()]
        );
        $admin->forceFill(['email_verified_at' => $admin->email_verified_at ?? now()])->save();
        $admin->syncRoles(['Admin']);

        $supervisor = User::firstOrCreate(
            ['email' => 'supervisor@gledco.test'],
            ['name' => 'Branch Supervisor', 'password' => bcrypt('password'), 'branch_id' => $cebu->id, 'job_title' => 'Branch Supervisor', 'email_verified_at' => now()]
        );
        $supervisor->forceFill(['email_verified_at' => $supervisor->email_verified_at ?? now()])->save();
        $supervisor->syncRoles(['Supervisor']);

        $employee = User::firstOrCreate(
            ['email' => 'employee@gledco.test'],
            ['name' => 'Internal Employee', 'password' => bcrypt('password'), 'branch_id' => $cebu->id, 'job_title' => 'Staff', 'email_verified_at' => now()]
        );
        $employee->forceFill(['email_verified_at' => $employee->email_verified_at ?? now()])->save();
        $employee->syncRoles(['Employee']);

        $customer = User::firstOrCreate(
            ['email' => 'customer@gledco.test'],
            ['name' => 'Customer Account', 'password' => bcrypt('password'), 'branch_id' => $manila->id, 'email_verified_at' => now()]
        );
        $customer->forceFill(['email_verified_at' => $customer->email_verified_at ?? now()])->save();
        $customer->syncRoles(['Customer']);

        Complaint::firstOrCreate(
            ['reference_no' => 'CMP-DEMO01'],
            [
                'branch_id' => $cebu->id,
                'submitted_by_user_id' => $customer->id,
                'assigned_to_user_id' => $supervisor->id,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'subject' => 'Delayed complaint resolution',
                'category' => 'service',
                'priority' => 'high',
                'status' => 'in_progress',
                'message' => 'Following up on a complaint that has not been resolved within the expected SLA.',
            ]
        );

        Feedback::firstOrCreate(
            ['subject' => 'Improve branch intake workflow'],
            [
                'branch_id' => $cebu->id,
                'user_id' => $employee->id,
                'name' => $employee->name,
                'email' => $employee->email,
                'type' => 'internal',
                'status' => 'submitted',
                'message' => 'The branch would benefit from a simpler intake workflow and clearer handoff notes.',
            ]
        );

        $operationsDepartment = Department::firstOrCreate(
            ['branch_id' => $cebu->id, 'code' => 'OPS'],
            ['name' => 'Operations Department', 'description' => 'Handles branch operations, shift concerns, and incident routing.', 'status' => 'active']
        );

        EmployeeFeedback::firstOrCreate(
            ['reference_no' => 'EFB-DEMO01'],
            [
                'user_id' => $employee->id,
                'branch_id' => $cebu->id,
                'department_id' => $operationsDepartment->id,
                'employee_name' => $employee->name,
                'category' => 'operational_concern',
                'subject' => 'Night shift turnover notes need standardization',
                'description' => 'Turnover notes are inconsistent between shifts and important pump maintenance issues are sometimes missed.',
                'priority' => 'high',
                'status' => 'under_review',
                'is_anonymous' => false,
                'last_status_at' => now(),
            ]
        );
    }
}
