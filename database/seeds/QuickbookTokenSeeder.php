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
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..8uIUgmjeloGELamEvgqGIg.73EKu3xZ7smb7uCRs0orMyxDwLkk8__prqTzegBu4uJ07UBSZ8W8BbDy7AmETMqOl5Bt3SkVrFI9DdLjTxegrjjSZJw9-9laFjrjHuIZjMDAjl2Adx0Mk1Pbi-efOB8xSkE50nPF18HXMrb8ebk3alnJccyK_f5BZOQEe08L0kRQ0OcNhpwqsV40GiGGzJVoAHwncDnWkd76CqNSW3WqBzfH3XfHFGfXABkBC2wJz8yfoUKyPaPq5zTr7yB36Bkw8ANtpMePAC38jsl08IRprDG8M7e28A74nH9AcGfedQnGIYJXpHnU9XPDz6a-UQONhE2l51C_8iABlkgLjoL-SGBliRVn05QruVfJ33tZTXhFRujJAwu_ccQ1CWunh9tDPVGqOgWFNkdXzfK8cUFO-bWnxYdkNGqHPqUWi4SKqGOBEYD4NkYmfKOLpCY8xQ6uwcIH4LZ-E8CyGDrMBTDgiDoVFLbFRwNMU8ZHTXN38DPMCDLo6WLf__9oz12-3TWPZixcRBZCbaVGDys2rcwImpn8wDCmf4J6KDk0-LJ-RAtV4MsgZcskPO5B7u-QWmPY4eLjWwqp1FmV3mFc7K8kkVDpt0xoRppp2A_btmHP8FzNH-uBKM24GxBLsNCE1FLqGbUsqj8sjX2j6t4-RNdde01f8o4VxbQdC4pQHGjoPJGhRZyjIuxUe07Ss5SIbZyLnqLW-o41xk2gprXTEb7B4SDSR6dKnH_8BuRoXY71ibc.vrSVLlw-DRYGNZcInbRyEw',
                        'refresh_token' => 'AB11599380537duXN0affkdl5N52DTHD7wpQt0eTCsqy0x0izy'
                    ),
                    //Plus GST account
                    array(
                        'id' => '2',
                        'client' => 'ABSSab3mSZlIGJLQ1tCZSKKpd7LN7OI3zacIfFW0hLsHLVMnyp',
                        'secret' => 'PQLjc67R23HENkuvNmgj2YY3QVn1OcBHXNtIJvpP',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..no6xhCy2XgJES2--iBt60Q.5cxm-I1j7S0Xi5tGxm01WIGUaPN5iHbETuJgeirlUyBVt9Kl0FA8JUoqFPDv7uMV7YhxMumk666DlVxwcy8ywKW63t-Wid6UUKsuLSExITF65rsS9cD2PSgeYHO4lO2EcLkJY-8-P4dpS0cO72RIYkbACHnaDy0TKOKEGydLM0knmcWMqhf0dyDyO728ZFCHkuq0rHNGuK_4y5KAm3Otv7CuTxZoFyb0O27dYORKIwN5RFSVsLzwifhdf4GbxHCMfUvINg_cDt3rHUAVzTBxZDmxvx7Jlyl_sH85F2wYzohN8Tf2tCPNGeucn_3ye3W3VKoNMoK9npiHcdbbXwvXnmno0QATi4BfrMK9KXPXcIxwdL9sZOsdfHcZdUWCxVAsu2aNDmzayLEWvLiWvFuDpHMrSU10QOF3zHVIS_0rtmB4dyjyCnoopw102d_ZL_wdsnuSS9bz146O0YcuyhiSngvRv_yAjLagc6bGQEXqH-DWD8sx4kcOr_0uN9v3-aJQy2V_cKxaOk1JIGAwnl93jjQZG0504NoJsIlz7qM5TIqhO7uSOXrz8H_4XMYjQ4sYqm-P8hFmAOSeONiat6Wtws_ZV7VgtTGezlC3oxtHsiJJC0qqPyqcka7iVciUstwEWHGXrU9DXGL-VHCFzxfEo33qcsIWI_1Idm3E-qz2gatFAWgIT3jdIALjPENhEkCk2_HArFIvnjk-3OIuMn4cQL-MX6vM4p-6Z2pXIjiXjf0._RDXjQoU0R1Cwto0q4yQxQ',
                        'refresh_token' => 'AB11599380368GttLYOzYHNLe5iyOdzV1vw2i9NYRFWPRGXQPH'
                    )
        ));
    }
}
