<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\WorkingHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    /**
     * Display the general settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function general()
    {
        $settings = Setting::where('group', 'general')->get()->keyBy('key');
        
        return view('admin.settings.general', compact('settings'));
    }
    
    /**
     * Update the general settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateGeneral(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg,gif|max:1024',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            // Update site name
            Setting::set('site_name', $request->site_name, 'general', 'Tên cửa hàng');
            
            // Update site description
            Setting::set('site_description', $request->site_description, 'general', 'Mô tả cửa hàng');
            
            // Update logo if provided
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('images', 'public');
                
                // Delete old logo if exists
                $oldLogo = Setting::get('logo');
                if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }
                
                Setting::set('logo', $logoPath, 'appearance', 'Logo cửa hàng');
            }
            
            // Update favicon if provided
            if ($request->hasFile('favicon')) {
                $faviconPath = $request->file('favicon')->store('images', 'public');
                
                // Delete old favicon if exists
                $oldFavicon = Setting::get('favicon');
                if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                    Storage::disk('public')->delete($oldFavicon);
                }
                
                Setting::set('favicon', $faviconPath, 'appearance', 'Favicon cửa hàng');
            }
            
            return redirect()->route('admin.settings.general')
                ->with('success', 'Cài đặt chung đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật cài đặt chung: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Display the contact settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function contact()
    {
        $settings = Setting::where('group', 'contact')->get()->keyBy('key');
        
        return view('admin.settings.contact', compact('settings'));
    }
    
    /**
     * Update the contact settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'google_maps_embed' => 'nullable|string|max:2000',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            // Update contact email
            Setting::set('contact_email', $request->contact_email, 'contact', 'Email liên hệ');
            
            // Update contact phone
            Setting::set('contact_phone', $request->contact_phone, 'contact', 'Số điện thoại liên hệ');
            
            // Update address
            Setting::set('address', $request->address, 'contact', 'Địa chỉ cửa hàng');
            
            // Update Google Maps embed code
            Setting::set('google_maps_embed', $request->google_maps_embed, 'contact', 'Mã nhúng Google Maps');
            
            // Update social media URLs
            Setting::set('facebook_url', $request->facebook_url, 'social', 'URL Facebook');
            Setting::set('instagram_url', $request->instagram_url, 'social', 'URL Instagram');
            Setting::set('twitter_url', $request->twitter_url, 'social', 'URL Twitter');
            Setting::set('youtube_url', $request->youtube_url, 'social', 'URL YouTube');
            
            return redirect()->route('admin.settings.contact')
                ->with('success', 'Cài đặt liên hệ đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật cài đặt liên hệ: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Display the payment settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function payment()
    {
        $settings = Setting::where('group', 'payment')->get()->keyBy('key');
        
        return view('admin.settings.payment', compact('settings'));
    }
    
    /**
     * Update the payment settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency' => 'required|string|max:10',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'tax_id' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            // Update currency
            Setting::set('currency', $request->currency, 'payment', 'Đơn vị tiền tệ');
            
            // Update tax rate
            Setting::set('tax_rate', $request->tax_rate, 'payment', 'Thuế suất (%)');
            
            // Update tax ID
            Setting::set('tax_id', $request->tax_id, 'payment', 'Mã số thuế');
            
            // Update bank account information
            Setting::set('bank_account_name', $request->bank_account_name, 'payment', 'Tên tài khoản ngân hàng');
            Setting::set('bank_account_number', $request->bank_account_number, 'payment', 'Số tài khoản ngân hàng');
            Setting::set('bank_name', $request->bank_name, 'payment', 'Tên ngân hàng');
            
            return redirect()->route('admin.settings.payment')
                ->with('success', 'Cài đặt thanh toán đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật cài đặt thanh toán: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Display the working hours settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function workingHours()
    {
        $workingHours = WorkingHour::orderBy('day_of_week')->get();
        
        return view('admin.settings.working_hours', compact('workingHours'));
    }
    
    /**
     * Update the working hours settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateWorkingHours(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'working_hours' => 'required|array',
            'working_hours.*.id' => 'required|exists:working_hours,id',
            'working_hours.*.is_closed' => 'boolean',
            'working_hours.*.open_time' => 'nullable|required_if:working_hours.*.is_closed,0|date_format:H:i',
            'working_hours.*.close_time' => 'nullable|required_if:working_hours.*.is_closed,0|date_format:H:i|after:working_hours.*.open_time',
            'working_hours.*.note' => 'nullable|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            foreach ($request->working_hours as $data) {
                $workingHour = WorkingHour::find($data['id']);
                
                if ($workingHour) {
                    $workingHour->update([
                        'is_closed' => isset($data['is_closed']) ? true : false,
                        'open_time' => isset($data['is_closed']) ? null : $data['open_time'],
                        'close_time' => isset($data['is_closed']) ? null : $data['close_time'],
                        'note' => $data['note'] ?? null,
                    ]);
                }
            }
            
            return redirect()->route('admin.settings.working-hours')
                ->with('success', 'Giờ làm việc đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật giờ làm việc: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Display the email settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function email()
    {
        $settings = Setting::where('group', 'email')->get()->keyBy('key');
        
        return view('admin.settings.email', compact('settings'));
    }
    
    /**
     * Update the email settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|integer|min:1|max:65535',
            'mail_username' => 'required|string|max:255',
            'mail_password' => 'required|string|max:255',
            'mail_encryption' => 'required|in:tls,ssl,null',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            // Update email settings
            Setting::set('mail_from_address', $request->mail_from_address, 'email', 'Địa chỉ email gửi');
            Setting::set('mail_from_name', $request->mail_from_name, 'email', 'Tên người gửi');
            Setting::set('mail_host', $request->mail_host, 'email', 'Máy chủ SMTP');
            Setting::set('mail_port', $request->mail_port, 'email', 'Cổng SMTP');
            Setting::set('mail_username', $request->mail_username, 'email', 'Tên đăng nhập SMTP');
            Setting::set('mail_password', $request->mail_password, 'email', 'Mật khẩu SMTP', false);
            Setting::set('mail_encryption', $request->mail_encryption === 'null' ? null : $request->mail_encryption, 'email', 'Mã hóa SMTP');
            
            // Update mail configuration
            config([
                'mail.from.address' => $request->mail_from_address,
                'mail.from.name' => $request->mail_from_name,
                'mail.mailers.smtp.host' => $request->mail_host,
                'mail.mailers.smtp.port' => $request->mail_port,
                'mail.mailers.smtp.username' => $request->mail_username,
                'mail.mailers.smtp.password' => $request->mail_password,
                'mail.mailers.smtp.encryption' => $request->mail_encryption === 'null' ? null : $request->mail_encryption,
            ]);
            
            return redirect()->route('admin.settings.email')
                ->with('success', 'Cài đặt email đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật cài đặt email: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Send a test email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendTestEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_email' => 'required|email|max:255',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            $to = $request->test_email;
            $subject = 'Kiểm tra cài đặt email';
            $message = 'Đây là email kiểm tra từ hệ thống ' . Setting::get('site_name', 'NTU Health Booking') . '. Nếu bạn nhận được email này, cài đặt email của bạn đã hoạt động bình thường.';
            
            \Mail::raw($message, function ($mail) use ($to, $subject) {
                $mail->to($to)
                    ->subject($subject);
            });
            
            return redirect()->route('admin.settings.email')
                ->with('success', 'Email kiểm tra đã được gửi thành công đến ' . $to);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi gửi email kiểm tra: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Clear all cache.
     *
     * @return \Illuminate\Http\Response
     */
    public function clearCache()
    {
        try {
            Cache::flush();
            
            return redirect()->back()
                ->with('success', 'Cache đã được xóa thành công.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi xóa cache: ' . $e->getMessage());
        }
    }
}
