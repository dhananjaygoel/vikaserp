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
                        'client' => 'ABOXTr7RAmUXVmeE82mITkaYze5K6dQmKSLKCZEoksLswpq6CF',
                        'secret' => 'uSaSnRgqEoZS5mv9U06V83iUKJhlSke8vAXPyi6i',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..rqM5QvQO3jd_3nQ7cyTQbw.nZxWeFWU5dCORBztsX_TOEr5xb4nilexouxfANZpGGhz2Q3QwFwj-jD4vXoYEZu3-oxbLLNzDESzrPQZPdOtIiO9_tOTWcgNwNniVcqqr9r2WLwMPrUtotEnDQ8pLrfsSObvMWVTdxa7HFOMG405AHoaxr-nGDpsG0TdN5Tlb6ATwTOTgVgbA8iw5QnGcWbsTc-oVeMxifPIOTUPfwglYIGUpMlbpb8cR_82FR77oBOgarqmnsTOLVAFa2U420_J3XS_drAZyYZDJp555TlwD98NBIP69d79YURlu9DCczOMlKwouHvSfbUKnBLeyBa6-UVcQHdAzA6lU8Zuep98XMLPkPUU-LIarbSSl7-n9vBuIRxWlNpvsPmIEs_30dFZW6HtZZYOCuAHA3xt_0PXZ-PU86aq5RQhHeTuYBYSCaXoL9Z4BH6hl4SaWckMqR-dYB8zSjXpcJXrMKCn2d_nGjC5E_tiu4Dd-63WWAAPa67mdoaLSOi4bK3MlTwdqhLZ2FC0RAyTzCXAINfw0muCCZwlVw_6OcLZR8y-6fiIKEgqvSNaehvfhxcN4i393_KKs1zlZa5DJK5Rpf1vy-19E8YGKiLTZB8747__YQvt4xg5aJOH7GYA2FBUxckVCoyt8-pLeNg1iXSdX29wTn5DuHtVz6r1_iPkbbnaBncfUMdhGniEggJ0bSibDCW_tdD9rlcnbj7PRIfMIeMSnvbFON0lRQwJMCX_ihBB_Dcalo0.4da_Tizzbq2MrFpwa5ouRg',
                        'refresh_token' => 'AB11602400077OOG26lun69snZVZDGNHK29Gp678Mbvsv5epH7'
                    ),
                    //Plus GST account
                    array(
                        'id' => '2',
                        'client' => 'ABKxbBba5bH6RNqtT6d83ZW4wFtrtPVJq1XgB8T0qZ09RHdC1L',
                        'secret' => 'bygKYjkZhpGLm4JiStyNFKfC6ahlzBKKtvXDjSs8',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..HdNK6Nmx24gtNRYicuTvvQ.-3MC7B7Cxp65DRFfnLiDGIefHJmk4cVkdTw_1s7OhJdva9-TXl94sBMTeeAtm9aC0MxN0iwsu7FliRHgg5iNif4DKUNqKOO8AQa9IDu1DdbrKO0fjeNmrQVI1W1ZSJeO-18uzmy-eXxrc9ulbTmOAiCl25fARKQjCMiAtnmcvqRWoQ1jNwaiF_VP7--0Sw3IY8uUXcOUr-oe4qhDmqH0-nk8jr_NJTZzhizaiY8vFKng4ezzgI1l-AkMF-Nnc9EEijDLzRdJrJzTF12EffCdbIVVADfxe-QdnXVmGAzjGyB6joljdU9Me9u7JJUmNlVDU3lAL2NQbxIhg8zoU4J2k_9eqHavXJl_IGeGLVxyjfcjidNW6Lvgdvcv2KCfCkc4CohNelJBO1Y7KfWUrIJBNCF9E5_FGSAPLDXXhp1njb0CFgwviUWMgnn6u_z5zsRgJbHcmR5yaRrv26JuyFE5wKoYaRT-B1BB456Sp2vpkF07W4aDsZQb7khJtxX3VcbpqBKJ2Lk3dwJ0-LDZVZq6noc43mDAorqKhNBfRl7xrfmJLd-8iqSKPBYTFB_E79COYPsrds8f6xx2TCZzraXsTP9Zm1BktSthXR86FyadHfR4SIWSyHmH1m8shacIvP77g79AgTLj0zAYuk_mYM_8nxtYYKrT-W2vwR_Ja4hSZqWi3anamX-0s2w48nWqZ445pkQZiWlF2Y6qMcAHaVAGcNqf86kwJ04UpvVy1r5IGXA.0A_AyTqwvzH1UeaPMZ8x9w',
                        'refresh_token' => 'AB11602400003ML7aeMDNz4HPJks9PKANIbmeDk4QVuiGTvIPC'
                    )
        ));
        echo 'success';
    }
}
