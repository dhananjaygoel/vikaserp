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
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..WndMXEWJECJXj4jCwASQVA.9TfTQQxPV8-WdZe5PCVGLUFkEMJ4ShSOOvXGlX4zzkj5twl39d_gW_Ix2H91OrFrXb0PAWejIvCAdopv3kyhqcrozzswK3a-xZq1mED9-ePxsliWnXilBLdx5is6_c2RLvxaez9ZqGaNxQ0Rbszgivee7I3_m_LVtNPbVavq88eKlCk9YiKdH-JRBK6tRLq5kjQ7cnTpE7c1pq7cj7VPopHEfG-66UbrJ7VqeDc-7z10dATHhdR2Si5Ro3CyNgNvhJ-8tH07n-Q2xp4DhE3oD1rqokb1qgfd2PvZ-nKROVSIwpHw92K11dnnYoS2YaI8UoKK1fUTxZihmgKTAKg6CsiNw-3VK1vFez8pT3Dw-T4Pd6XB8XKEUiIrFY71m9buC86odZnXAg5BNzsSWf_B6Sw7_nUi0SCwKSZbKoiNLO3HoeseMBtvXIssSIciY9PoSnkYM_g2l8i0TwlNpzfX4s5NCMBjtRRUdMmIK___rzbqi6JpvKf9bcuTNpzRMOstEiiimvOb57J8_0dq5dMRi-hTKnIqE0Qy7Dj-ZIVhXTlHlSb3k6tf2RDSfIYe8K-lSmzOZHqbRcDHIctZU4sJqHxq9Vy7gixppgdlKOz9Gfv8s6bt1HrnTQ4YjND2BzeNUZ74fBIGvOom9JE3b10akapLVoF7ms6U-qjm0Ze9XJhxjwY3J6G0Gj0gRsbYzypLqIEB17Jw_6WXO10LW9JA4xvHNgRQGt4iqkP2uzqlEws.oZ9XABRSohjthylF-7vepQ',
                        'refresh_token' => 'AB11603018931wJI1Y2cCXg6Gjrr6o30X3DtU4AcNfD33eFyMc'
                    ),
                    //Plus GST account
                    array(
                        'id' => '2',
                        'client' => 'ABKxbBba5bH6RNqtT6d83ZW4wFtrtPVJq1XgB8T0qZ09RHdC1L',
                        'secret' => 'bygKYjkZhpGLm4JiStyNFKfC6ahlzBKKtvXDjSs8',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..ptZDVqmdYcmrH7pMgrOmOg.UGyrIYnVLcV_OCWEm7BAGyR0q1uPzHHw2iK090Z4RPiM4lsv2k21kFLP-5Z7Yom42JUhG0BwyhcvxccEpPU8uVtnCPtUgTD0y_T6cP_YmeyzVEJudylgKn5thjFxJauwCfmMEKOt8iM42NaJm_YSGIA9fmvIJadUxK3wzRMN90SoB8igTdSAg4j_j_iQyy7GalMP3cMZDJDD8gyr2geW0ElSsMQEwJzMZOILLCzsTO_WaYH8uFqceRigyFeTOKS-pagXaHh4MLn4wyw5OB07SXqgR1E5Oycp_CeZOYg5D4HXCw0R1qiEEdkZT7TxeXpnmtVO8NgAbaMspeUFhcfjRTnovTQpDDHbMvJRFg1W39ZIdNcRSMkPmJqGBb3rSTEZaIRONlU18EcAx8zIyc38VITC2k-Mxf-GnPM7rxPDEAoa3YxkOdf59bja108xEr7gWY5ci1Xi550-nDkygXbqDk1KVxqCqk2tecCkdD_IpF7R8AG5AH6jftfEPde1Jp4A8aWZ5gxncSTDLhwAJpgzMGUOpuDoqg4aClGRfI-wLZyMO6ACM-SdN0HG-a45pZDTOSFoXcnA8dqHjl900ON634Njp--UDmpIOz_DsoUTi4q0TnpUrLVO4h5J2W2spVwP760NfaquA2GPXwKORsnOhm_Eb3gkav74ideTaPlYv0w1r3t-QXJONsnWdjRcQkJuUJQEyOiPUuMy9ZMcn_XiuuKMkaxqBuGCyuJFpiMugBM.-dnJX62PCYZeFUpcjWqNzg',
                        'refresh_token' => 'AB116030189842GX6DYD0iVEtumdkXlzF9mxEbC3qD8FcPzmBJ'
                    )
        ));
        echo 'success';
    }
}
