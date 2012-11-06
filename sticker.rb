require 'barby/outputter/prawn_outputter'
require 'prawn/measurement_extensions'
require 'barby/barcode/ean_13'

pdf = Prawn::Document.new(:page_size => "A4", :page_layout => :landscape, :left_margin => 6.mm, :top_margin => 10.mm, :bottom_margin => 8.mm, :right_margin => 12.mm)

#declare all defaults
pdf.font("Helvetica", :size => 9)

pdf.define_grid(:columns => 5, :rows => 7, :column_gutter => 6.mm, :row_gutter => 4.mm)

#-----------------------------------------

i = 0

@order.lineitems.each do |lineitem|
  
  number_items = (lineitem.quantity.to_f / 5).ceil * 5

  number_items.times do
  
    pos = i % 35                    # pos = label's position on the page (0-19)    

    box = pdf.grid(pos / 5, pos % 5)    # lay labels out in 4 columns
    
    pdf.start_new_page if pos == 0  
    
    font_size = 8

    # (print label in box)  
    pdf.bounding_box box.top_left, :width => box.width, :height => box.height do     
  
      pdf.draw_text lineitem.label.article, :at => [10,55], :size => font_size
      pdf.draw_text lineitem.label.product_number, :at => [10,46], :size => font_size
  
      pdf.draw_text lineitem.label.size, :at => [50,46], :size => font_size
  
      pdf.draw_text lineitem.label.color_number, :at => [70,55], :size => font_size
      pdf.draw_text "€", :at => [75,46], :size => font_size, :style => :bold
      
      pdf.text_box lineitem.label.color, :at => [82,60.5], :width => 50, :align => :right, :size => font_size
      pdf.draw_text lineitem.label.price_to_float, :at => [105,46], :size => font_size, :style => :bold
  
      #generate barcode with Barby library  
      lineitem.label.barcode.annotate_pdf(pdf, :height => 23, :x => 24, :y => 17)

      #generate EAN-code underneath the barcode
      pdf.draw_text lineitem.label.ean.to_s, :at => [36,8], :size => 10

      pdf.stroke_color = 'cccccc'

      #pdf.stroke do
      #  pdf.rectangle(pdf.bounds.top_left, box.width, box.height)
      #end
    
  
    end
    
    i += 1 
    
  end
end