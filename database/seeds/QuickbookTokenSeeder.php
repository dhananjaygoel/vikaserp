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
                        'client' => 'ABnqVTjOQ9r5VZO7S5uw3KQABUQIGAYwZJZUv5V3xwXrJ17Gcz',
                        'secret' => 'nIyVCespee844MyX4afCosxLlz5v7kazICCjjIPx',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..pKlK3zxcLwNxWW_OJ5dTMw.O1GWds3jQJA9vSRNnKSzXHruFM_c6_ALLPVuLi-LrNA6iLqcWksprzZEGAQrhTlfy6L0WmV-1_ZE8ZbTKo-aYrEmm3PcrA5ye_bI3PcF1bX4tErK4r5n-I237-geNoCsGiak7nFVthS79FRml604b7Q9tQRNWoHlbCTfgf6RxIQ6YyByeNhswpxVvJUUdeF5MFgEsOXKT6OzHsEBDBYequPCevTVCqeeikvl67vwbUK1DokRI2I9pZSht_K87YawGrfNaobjinnuTVPCxT3M1N_HhT1y-X-IwRluWVUOEXEJyKfrGj87EwDtgcjyrG4NcXLRpNUVHi4HsCkq4CmZv7pwfBPj1fhw3_EeeCfRO72U9hLoSSSYJw3qxEBhBIIiSgc21ZAOb8N_OlGfSwj8Lwl_cUCQ__kFiLjzlNJkhCQu_1YB88vTeM5DRgbvxz49hJCBZ6SdqOgh8TnT-TznxT0nIyxCP5HnlQocDTP9C3YmvxE9WiSsutrR7gw5VzMrnfaD18HfzYWCmGL7hyamd_FiE4f-6jWkKx6j0j_paMqkdJhWw3UAj641xsp6C04wpCPUGkAPYYv-de84qkNA9-h6WlYum0jCyqWE4HW5tEP0FZvqLzPEkvHA8jGdWcz5AON3iQrxIpVv13FsgOeE4y--JuuqKfDKi_jHpdw2KjWcsF6KEvnI4QXG_HNC4IplUbYiTXtoE5KYITSG638bI-PI25_nPvNlu7RxpJ3dAg8.CDOkDmDr2g21ZVwSENe52Q',
                        'refresh_token' => 'AB11584958079GmdImJ5XRpBBZyVsmLdJMvjKwK1oUerYvF4qW'
                    ),
                    //Plus GST account
                    array(
                        'id' => '2',
                        'client' => 'ABpB0F7pZAhYnM9VSVDYV7yjN2LAkIMurjpdb7euYYuPllEnVE',
                        'secret' => 'pOsgW109PbXD87sQFKEDtzoEzAuJrKfvV6yZLIYG',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..pBJLLhrppXSvXCM2CPU_6w.l6ojBV4wN89d1MLMLi5pz0nH0FssDikTmZxwx1LOrngWrjTes9TgX1zdNhHFFrmoOq2wI9rx89PZrWSFGoTgO-t-BJlpzNcvLNowxKk-8ZB7FdJDrG78HbWw3KwOioNwkreAPfgB6mjGsjqfCuVD3aGQuJJ6jbARIkQ-14IMFX5yUrW7msn4ltVNbrOFoeAIIQQsIotsyREoyucC1aBQHNMPvvPm3gORkVhzZlBz72SJXSqkh-JM6qUXwDeAOjJ3BXZy5bHeVWcXMBp16Rmx4S3L4794jXUBWw0-YkkpiW0V2dGFjM7Q4EYZnyaOUpabeO8VFF4z1pWj_T27ceKpON6qv5cmz2jY8AhXgcLKyy1uN_wrguCuZO-O59FmK2sqNRxy7431U3znpe1LQV2OvAcrSHkt8OmgF_OTvIXRc57Z8dhnuFRzEtw3O-yAXYVbWczkJSHSU3DevQ18oPif2AdQzMnIbx1P35LV8WbI7ahg8o2sXIRmznMUEaw32LVfcWeldSttAjnCYaiTCUBmJEYpPbj6GtRSFzdczTc07e3cHhR-xKZpOhWP69IL5tZMAJwqzdPKYzas6iPkssRDM56i63JRUkGIMkf7rkuDcyjEM_e2fksMOjfecMYpeGG6v0hmn31oRlRWWIH7Sae-ohgt75Pks7_5U5rrwnu_5HXuatRiVbxXt6Xy7MUVBW1MoXqJB7LmRVkw3OYtoPltrLZFiP4ftDid1IqA0zcC4K4.4Gomnem_jamA8hlpb6klLw',
                        'refresh_token' => 'AB11584958635R5evCBLpDRlDY8sofniSJoX88iC8B0WNnf6cq'
                    )
        ));
    }
}
