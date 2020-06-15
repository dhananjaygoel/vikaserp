<?php

use Illuminate\Database\Seeder;

class QuickbookTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    public function run()
    {
        DB::table('quickbook_token')->truncate();
        DB::table('quickbook_token')->insert(
                array(
                    //All inclusive account
                    array(
                        'id' => '1',
                        'client' => 'ABfN2ld2b7J2FTN22uTqLPCFkx9f99Sj4YvDsMmVHD3mNUyuWU',
                        'secret' => '9peqxCWOzdpp1cEKPnAsPAAyEz77Z930q2QADnoe',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..hk13mXf4cZOGRSzB--zsLw.TZxJZLrum3spJztNJocN3BdkQ01TyhDJETDBMm95uAviJ-rkf0tdYEZZi8R49fe4EJnW139ihUczsU4b3mpuEDTfoeePiXooSsseyMeRq5OIGsnPG_Wp_A-xJaLdSL3NBsYtE6pP8HsaAD5PSg5mJIn47sES7beEk-m4yGZR77oKVYJDZPytw5up8vmZ8-eViX8QiU97J8YLUAhhGVuj1M1drnPFeJqOOcQ_pWiZ9Sm_lEQWutvMnfr8eVna-hlovVK4O29IJsV2iH3re9rAMff_40rGkZHihhhbGbZK62H6m9Ghv5DW39XgKu9vMWJPpQ93QDxehGrWCl2Of_9QWOnMIClAiYrTZ5LqrgC4gO0hWamebM6MgGWkZkJ-98fQlaPwQio1b_OoQiIc0tSnWROEvdcw3ksTOKDtt2Rf2NEOmcn6ljblaHjABjN6NmvvMf4wZmZQmdHC15LWOG-SM4d3Ljo22bzcUp8fc3950I3z81OU2gXGxre8zu8xIiDbY9L7ECspQ8f6MzRPfSZ70povk4qoo7iOa5-ZVve69GMIUqswJkIs2MEa7hu6ggEU4WSHaBff5FBBC4ITygMvZC_q_8IxHHW_YZ5ulOoy-py8qXl-RJz6SP3GWMIuTjJv-w38wEMF35T1lZN6LZbSYtCqW55rWXfCtDpp_fI6KbNx5aZwuiWDtCv9Hz8EkSAGn5sM9k7QiTgw7hF23fGHLg6bE0cd7vjtuxn-8cd-y0Y.SNxnVZQ7EAOkn_JC4q8Stw',
                        'refresh_token' => 'AB11600945246NHtFNupKyaZPDBC2y1iiovpClJifLFdcBuweb'
                    ),
                    //Plus GST account
                    array(
                        'id' => '2',
                        'client' => 'ABSSab3mSZlIGJLQ1tCZSKKpd7LN7OI3zacIfFW0hLsHLVMnyp',
                        'secret' => 'PQLjc67R23HENkuvNmgj2YY3QVn1OcBHXNtIJvpP',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..hk13mXf4cZOGRSzB--zsLw.TZxJZLrum3spJztNJocN3BdkQ01TyhDJETDBMm95uAviJ-rkf0tdYEZZi8R49fe4EJnW139ihUczsU4b3mpuEDTfoeePiXooSsseyMeRq5OIGsnPG_Wp_A-xJaLdSL3NBsYtE6pP8HsaAD5PSg5mJIn47sES7beEk-m4yGZR77oKVYJDZPytw5up8vmZ8-eViX8QiU97J8YLUAhhGVuj1M1drnPFeJqOOcQ_pWiZ9Sm_lEQWutvMnfr8eVna-hlovVK4O29IJsV2iH3re9rAMff_40rGkZHihhhbGbZK62H6m9Ghv5DW39XgKu9vMWJPpQ93QDxehGrWCl2Of_9QWOnMIClAiYrTZ5LqrgC4gO0hWamebM6MgGWkZkJ-98fQlaPwQio1b_OoQiIc0tSnWROEvdcw3ksTOKDtt2Rf2NEOmcn6ljblaHjABjN6NmvvMf4wZmZQmdHC15LWOG-SM4d3Ljo22bzcUp8fc3950I3z81OU2gXGxre8zu8xIiDbY9L7ECspQ8f6MzRPfSZ70povk4qoo7iOa5-ZVve69GMIUqswJkIs2MEa7hu6ggEU4WSHaBff5FBBC4ITygMvZC_q_8IxHHW_YZ5ulOoy-py8qXl-RJz6SP3GWMIuTjJv-w38wEMF35T1lZN6LZbSYtCqW55rWXfCtDpp_fI6KbNx5aZwuiWDtCv9Hz8EkSAGn5sM9k7QiTgw7hF23fGHLg6bE0cd7vjtuxn-8cd-y0Y.SNxnVZQ7EAOkn_JC4q8Stw',
                        'refresh_token' => 'AB11600944709H0uHOBnsikztg60q2GX5nePlowZeCUFd0RMg3'
                    )
        ));
    }
}
