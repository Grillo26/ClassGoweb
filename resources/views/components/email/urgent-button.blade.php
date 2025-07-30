@props(['btnText' => '', 'btnUrl' => '', 'urgency' => 'normal'])
<table width="100%" role="presentation" cellspacing="0" cellpadding="0" border="0">
    <tbody>
       <tr>
          <td style="font-size:0px;padding:0px;word-break:break-word" align="center">
             <div style="font-family:Helvetica,Arial,sans-serif; margin: 15px 0 15px;font-size:16px;line-height:20px;text-align:left;color:#4c4c4c;display: inline-block;">
                <a href="{{ $btnUrl }}" style="font-family:Helvetica,Arial,sans-serif;font-size:16px;line-height:20px;text-align:center;color:#fff; background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%); display: block; border-radius: 12px; padding: 15px 25px; text-decoration: none; font-weight: bold; box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3); border: 2px solid #ff4757; transition: all 0.3s ease;">{{ $btnText }}</a>
             </div>
          </td>
       </tr>
    </tbody>
 </table> 