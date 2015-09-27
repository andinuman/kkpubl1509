package com.andinuman.kkpubl1509;

import android.util.Base64;

import javax.crypto.Cipher;
import javax.crypto.spec.SecretKeySpec;

/**
 * Created by andinuman on 9/28/15.
 */
public class Kripto {
    public static String enkripsi(String pesan, String key){
        try {
            SecretKeySpec KS = new SecretKeySpec(key.getBytes(), "RC4");
            Cipher cipher = Cipher.getInstance("RC4");
            cipher.init(Cipher.ENCRYPT_MODE, KS);
            byte[] encrypted = cipher.doFinal(pesan.getBytes());
            return Base64.encodeToString(encrypted, Base64.NO_PADDING);
        } catch (Exception e) {
            return "ERROR:"+e.getMessage();
        }
    }

    public static String deskripsi(String chiperText, String key){
        try {

            SecretKeySpec KS = new SecretKeySpec(key.getBytes(), "Blowfish");
            Cipher cipher = Cipher.getInstance("Blowfish");
            cipher.init(Cipher.DECRYPT_MODE, KS);
            byte[] decrypted = cipher.doFinal(Base64.decode(chiperText, Base64.NO_PADDING));
            return new String(decrypted);
        } catch (Exception e) {
            return "ERROR";
        }
    }
}
