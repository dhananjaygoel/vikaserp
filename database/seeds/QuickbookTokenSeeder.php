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
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..9qt3iWsF2CO6pZHIFeRFFw.NKEIiA8Kj1N3ZubxtWskFpWTZOwgX3o7j8SdZvvLG0lnJj_y838FD0hlpPGWYndKr7jyf_-EkX0NbaOVAN2KdF14eLoXoZsELGLKpBZXL6YaZSwj9fWl03rSdXpPBMmbE_aUzMmfC3DRPuKPx9SmqdDQzsJ1eFzBxOhdatsG903m1ni-iHsIxPUqufhCjT0sVeTfDSo30nPFyW6bf5voBMLxMt602DKqqGv--0LlASpA6WU75mdCWFo2dhj1qEKdKpX_8-tIT3qWD7fEAeaptWODZaFmtPvdZDrTUPBRaR7sL5yNi4KP8XuBacCzG3Epti_noWNWGOq1TxwuUlEyP7NT4saMirNgjVCX1tQKHP2KToDCTFxveysDFrXWXzzw9sDtIJihAxkmla0Gw1YxoQP9dWPO0_fyepuDeIWs4Wb94P37wYRycP-PY1Mug8jV6TubovNDjBZcReNajSTNsrbrOJSfvLLwEfELy6qixDMKeTcoK3Li7opZQ-3GEc5L_Af-KTC2xiHc2sqK277E8BPdRUTuNb24K45ChfHq-Szzy_J2Dc-SrSsPQOH2mg68EIwYCPDM_m90ZHOdLkt3q3BJN5Ecw55EQ8NpT4dZh06rHa2hKGCWJ-5AbbABf2_WcmGzOqHtFJmprEP3qbEfHpart8ioYfOiWeah0K5eP42Ed-QNXYrPPkzuH5JIAxpyOQX2C97slwSflYumea3SGTT9bHP_V9ZD4wSdz4dMY8s.reclHr0K8KTFtTqkdjge7A',
                        'refresh_token' => 'AB11603951455Lx8y11ywJcEnl8vv8RNqanhRoDAvE7LpOjL4u'
                    ),
                    //Plus GST account
                    array(
                        'id' => '2',
                        'client' => 'ABKxbBba5bH6RNqtT6d83ZW4wFtrtPVJq1XgB8T0qZ09RHdC1L',
                        'secret' => 'bygKYjkZhpGLm4JiStyNFKfC6ahlzBKKtvXDjSs8',
                        'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..mg2LkHB4UepoPc5rwvasBg.74h5ptnOV-R-nbwqk5BWXyVUDhdpUm3yasMQOI3DZmuDhFebfgkSdXyD0TCBi7Mo8zwYEOR4HsBy_M4gI3gb7PXdR9ARakB_CQAPVnpgOPvsN4iSCJB2T3w46-wLVMM2yffujGZ3lV6FOw7J_Nm0An1U7LuI236UXzlJWkHn3fV_ghVsg9OThCyTWunGoSyAJ6ClER-fQZFjb0tBhjeV2V89qUw1TDDES0CA9eMwGsVfkAI0h0P88BOCWZQ-hgU1W4pNnWXSfRU_DB1lHVNtF3CVXFDfpZBzG0K9F0uBQfB9S9eufq1Tv6rvWkISqyCoNP0MXVFdlIHi7rweU0OgsCJgpZYaLzbPxWJuVtG5Oc6OiJIs8tVV57Kll_CyCIhUalzn7aCLxF_25K3goXUFouzofizveEbYs-bwxJCW31NW550uP6fADMvJ2UUFXJ0WQfNPIPGudd_vUQEoDs1vMJSBGCuzfo0UypnuvdX0VQ0Tnza731GpOBr-SRv8svc9X4N-pU_QOjJ6IbpbiHh3l38HWSqIkLFTFyixiSW3Ewteuvqz1HyS32ipgyHFYg91EmAYtJeVIJk4DJLvaqO88BEB8nvPApTyshGxIitCymu2LZ_I-txQ32afVMj0_mBiRh57HQ1FngkvfdhfDeWK7ym2wdctA-3ddlCcRQ_ctLv5UB8BceTmV74NGNnsGJr-Wb8jPDuNQvk-lfGqlCJPyTsUML9HLKKCextP2dY0JuQ.qIERUz1QRgJlGgUk-tq28A',
                        'refresh_token' => 'AB11603951380fscIvjDI1ndQjTvbL4jnvQxAuvtyVPFv1NWAg'
                    )
        ));
        echo 'success';
    }
}
