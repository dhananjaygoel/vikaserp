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
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..RiaxqYRGkFzq_Axedc-aTg.VUeIuDQfoLT9hvU-kLDN_6LK6Dk9lSmGvYYddI6Mah-vasrF3d7oiP8R3R9Y2JTcy64WHxiPk23Cn_u3TPdJ8HSSzqNbTm78s58g3hJJuiywXtCY9NglbKMQwTLb1H0xvnNcSQx7rnaqrooqmRTH6_EDhiyAe64TBAKPrX23URBRPSjQuVnql52FGzkl8HygBkhyQkuiB7Lym27TCBuZX5JhRBSKe9FY4auRiUdyn2U1aREpCtk-JuxQpt9Jtvn9piBomAjPWxDv_ktSBPFFz08qrw56TPJBRIUfrlAmjZFcKaftwCFY_WOzvkRkLRQYN81JqqVB5P7tx4II6iEiWAyXDt2r1sURyXs58jGSndf8vJ0D8ieKl8Bw3WDg6OIcxgSBsiD1Hlwujry4dD1Ef9YyjBjZAkDTKXFcSK0sC13-1Mx1Md5ZzgKHCRIKZbKeyAhpaFYRyWjcRQ7Ww3S883V9QNNyc8SE690k5X9tBgaziCZmp9yyb3_idp7Tr0AT7IVWwE7V0RcNlY_9r4Gr6MDIZO8cE4lHkRRbDDiBXVwP0sUG6UQDerolARi5Fb-9KI8nHQN4tQLE0px68PYl18JQ2mVfmSBZjOjMZwCR4ye6P2cSYGu9dPDlhv_yYELl3yPeXG2JWLXd9zLnzIDK_0SphZJl6TLt-0xhZvLDiNcPtuhJhWZ18z3vpWRp2J30TG6qhx7d94GzgFbz_YHTz8igGgtd5g9uEI41mcjXof8.WPYrvJyrRAgLgWQeAw7xVQ',
                        'refresh_token' => 'AB11603524204xR0jVNhM2Ys6sRaflAmDN2ps6SmzeQ9v1uN03'
                    ),
                    //Plus GST account
                    array(
                        'id' => '2',
                        'client' => 'ABKxbBba5bH6RNqtT6d83ZW4wFtrtPVJq1XgB8T0qZ09RHdC1L',
                        'secret' => 'bygKYjkZhpGLm4JiStyNFKfC6ahlzBKKtvXDjSs8',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..JZLiTfeYyAOdKb0nzxPBdw.Eh5ZcLmkxZIg6ERgL7-vsoxT50I_UM2UXuoIeq2-ArxCFGqVPnkugUPELkLtQXqc0WO_2DsNuPCIyMKpSG-crl45rTwLelr1jnEklA4v2FuxKvM_hWDimESBROXk65gF6YyJmfsjyrM8d93PmvdoJeSgVo-0HxqAlz4lUdEEPtJrzwA69YHGpXeGonpj8CbQbkNDyEhThJ9LdtVYywcWRlCWUzDTpg_-Wcvabz-k90oUBYZFri08IeBprcL2UiISkgxcC2MVioAev5JuaTDAXxRFqzdlCs6dPW84p3Y6Whbk3lXwBJ33q8_o41kBEzzC3z8jdT0tIsA7fZYXdHelL1hfaSc6c2gP3wMpavF5UAdlrX8Dd_nU4B5RklX7bQ34m2zzU99fWj8cq73-XqyxcUWnRufsQzMeBJCDrI-vt0krZWGT50v6JUIPuTIJkdMHFZl_iyBOX8dkk4CWXbcDNPm07EN02BeiwVXRT_BCuY1pm2TTlkaa-3oGoATkxhVtlccTX2jSFY3zDghiozDBxFSvM52VleTIIMETVy7PUUaC0QneBDVkhlNA9yVVn6yG0worG9nsf9fB1lobM2vLXD65HjSD6CwnwXdRbVtmDeX3vZQYJ7CJJGWDbvsnRd5bYMPFSjxqBbo127HQL-EMv9SKjt0e2g9W8LusZeKbdFlUsU7EOGBtLEcsci-e9VzIj3iDZE9Dg5gK6M47ZLd78E9L0XTU7Q4SuEdIrU5ppA8.9CKiV2F_7OB-v6RQsm73JQ',
                        'refresh_token' => 'AB11603524132IEfVqf5tcxusml23xmTPgjQOvWfv0T3e3DV5K'
                    )
        ));
        echo 'success';
    }
}
