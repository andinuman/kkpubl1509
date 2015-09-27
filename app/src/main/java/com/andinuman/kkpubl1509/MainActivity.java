package com.andinuman.kkpubl1509;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

public class MainActivity extends AppCompatActivity {

    private EditText edtPesan;
    private EditText edtKey;
    private EditText edtEnkrip;
    private Button btnEnkrip;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        initUI();
    }

    private void initUI() {
        edtPesan = (EditText) findViewById(R.id.edtPesan);
        edtKey = (EditText) findViewById(R.id.edtKey);
        edtEnkrip = (EditText) findViewById(R.id.edtEnkripsi);
        btnEnkrip = (Button) findViewById(R.id.buttonEnkripsiPesan);
        btnEnkrip.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                enkripsiPesan();
            }
        });
    }

    private void enkripsiPesan() {
        String pesan = edtPesan.getText().toString();
        String strKey=edtKey.getText().toString().trim();
        String chiperText= Kripto.enkripsi(pesan, strKey);
        edtEnkrip.setText(chiperText);
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();

        //noinspection SimplifiableIfStatement
        if (id == R.id.action_settings) {
            return true;
        }

        return super.onOptionsItemSelected(item);
    }
}
