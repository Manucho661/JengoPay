<div class="view-toggle">
  <button class="view-button active" id="button-1">Button 1</button>
  <button class="view-button" id="button-2">Button 2</button>
</div>

<style>
.view-toggle {
  display: flex;
  border-radius: 30px;
  background-color: #f8f9fa;
  padding: 6px;
  width: fit-content;
  margin: 15px 0;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  border: 1px solid #e0e0e0;
}

.view-button {
  border: none;
  padding: 10px 24px;
  border-radius: 24px;
  background: transparent;
  cursor: pointer;
  font-weight: 600;
  color: #6c757d;
  font-size: 14px;
  transition: all 0.3s ease;
  letter-spacing: 0.5px;
}

.view-button:hover {
  color: #495057;
  background-color: #e9ecef;
}

.view-button.active {
  background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
  color: white;
  box-shadow: 0 4px 8px rgba(106, 17, 203, 0.3);
}

.view-button:first-child {
  margin-right: 4px;
}
</style>

<script>
document.querySelectorAll('.view-button').forEach(button => {
  button.addEventListener('click', function() {
    document.querySelectorAll('.view-button').forEach(btn => btn.classList.remove('active'));
    this.classList.add('active');
    
    // Add your functionality here
    if (this.id === 'button-1') {
      console.log('Button 1 activated');
    } else {
      console.log('Button 2 activated');
    }
  });
});
</script>