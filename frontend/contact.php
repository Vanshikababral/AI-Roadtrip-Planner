
<div style="width: 60%; margin: auto; padding: 20px; background: #f9f9f9; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
  <h2 style="text-align: center; color: #333; margin-bottom: 20px;">Contact Us</h2>

  <form action="<?= $ROOT ?>/database/insert_contact.php" method="POST"
        style="display: flex; flex-direction: column; gap: 15px;">
        
    <input 
      type="text" 
      name="name"
      placeholder="Your Name" 
      required
      style="padding: 12px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);"
    >

    <input 
      type="email" 
      name="email"
      placeholder="Your Email" 
      required
      style="padding: 12px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);"
    >

    <textarea 
      name="message"
      placeholder="Your Message" 
      required
      style="padding: 12px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; height: 120px; box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1); resize: none;"
    ></textarea>

    <button 
      type="submit" 
      style="padding: 12px; background-color: #007bff; color: #fff; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; transition: background-color 0.3s ease;"
    >
      Submit
    </button>
  </form>
</div>

