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
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..fWz6LN1AJDCmgBw7w3BTqw.s7AkDgd8JwjwxbA8__DzwDtS8y4G-yO_lUrl_XQnPfhS9aq4skGOFm2ZTCC3U0-X2fGuUe1jAUeAiIQeSn2ZHL_vnQruMm-F7UiUg7LiKayi-VL3SU-1yVXRxN_2tRNB1flcA9SVFsIvXdJ8c-9CK4OZsBH96h5Z44CQPX4v4aXwaZjr18xwWQ1gAlrVAROgKVSEPVjeKIQMz1LUA-jiKQjah2328D8Bba2yRbvwj3knFRGWTDxOXC5Qz8--pb5VM1WQYQeyLkUtXblVfn3mODq18gqgisB8hIgOHX1KCIncfYdQuYs5loj04Zg-UHeuZb1Nh2YE8r30HBGe4SFXKm2bUqtcfkjUxGZWlALeypyntxGLzOFX7Kbs_W42YtezKpHaLidKn7Pn0bJ4ZfWp7OZNgiJX7H2550uRkMv-ZZ_GkCKvP-YTxhLYQjzNmnbT_vhGP14wIkSa31zlozcCYD_NHVAWyNuQPXnFAKq90APbNxDR7d3ALP2Et5aZpumA0Fn641yZPrkz_owkLiNgbP2oLunbKC-HQOknOqOt9blAEwOlK0d-HIERd8fgCaQbpG03hZS0-TIMTN6B2yl9aYzPPcAiwu3XOfRo6zvcBmbNPF3WPaW_d_CCxSuAe2G4a6adKI7k4lozWdoLKxHdj3qstMXD4EuOq7UP1c88QY1VgjU68S81FRawnla_vAoo_v1TZ9fihAv-W-HQ4r0I6-g5w9i2TRasUdsHz87JE24.jSTFo5pRrbWYknZyMy99hw',
                        'refresh_token' => 'AB11600682801j5pPZPFn76zHy1iCoU5B7zvqlUsCdGrbjPE7y'
                    ),
                    //Plus GST account
                    array(
                        'id' => '2',
                        'client' => 'ABSSab3mSZlIGJLQ1tCZSKKpd7LN7OI3zacIfFW0hLsHLVMnyp',
                        'secret' => 'PQLjc67R23HENkuvNmgj2YY3QVn1OcBHXNtIJvpP',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..Q3uQSKlPJsQ1quMAhfgELQ.LqRnSn5UyBp-8QfghfSX83o4K0bg3FsqJaAlHoICa0uHAFwsgkogT2_L929NSS0wscivLYJzvhs6JMeNdH-l6RRF6aK8BY5dTbOqiCLP2A2RMatIiAzfNNo15ujS6su1-OsB6J37ogoAZ1snt59IkbUFs7ttcZjkpHAmAIP7OfxFgth2YCLGpVyaIrjgwG1HcXb6qeURKIy80JXwzsSGD-KGFR5S6wQbrmXhqTw_sGf0uvU_pSULb_7NNF671PRH8JFysf4AmRQ_nsTqtQ_PWPOrmWN6CN1GKbndiyUHvuEkhi2wlyBhx7czoYEgjz65mUtm9Lbo0D2lCI1r3B6zzcj-UV65MI1562bEoSwmCW_CBcdbOiiQs9o64Fh3QtUa6fbKdytAssflH4X2LzDI4dr-aVkJCRFmgPtyRb3kuY4991wQD0A_VlbFzld-UK7hVDrkBwYShF6u65GAZiNdmk4y6kC8yo4tPc3Zp81eUn-TyHBzy490JEwacqsFv-EJk45W3obcIxCwRFp3suq3ximHLvWBS8ytheF3ndXrg02eIJkC4YkuMuXCdsd9pnu9Ub1r7U8FKZEPgFleNW6odA7B_Xsv-rcMvbtNFycrupceARFt5YnwohdyXEkgeeLuYzykD_jI6KzZAvYp1JDU1fDeZ3IKnpkbxJLOv7olqS8JXN_ay_lzS-6Tlsn9ccjZzRis08EiJ4wpwGNzKbgNfHnLazEvjtbBNEfQwmXYZVo.TVtWAajOfQAX8ygoIPlshQ',
                        'refresh_token' => 'AB11600682709B68UFcWUrh9tIRcjDwTyAWiKGEwQGH1Dq9KlG'
                    )
        ));
    }
}
