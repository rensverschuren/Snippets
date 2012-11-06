require 'digest/sha1'

class User < ActiveRecord::Base
  
  has_many :orders
  
  validates :first_name, :presence => true
  validates :last_name, :presence => true
  validates :email, :presence => true, :uniqueness => true
  validates :password, :presence => true, :confirmation => true
  
  before_create :create_hashed_password
  
  def full_name
    name = self.first_name + " " + self.last_name    
  end   
  
  def customer_name(full)
    if company.empty?
      if full
        "Meneer/mevrouw" + first_name + " " + last_name
      else
        first_name + " " + last_name
      end
    else 
      company
    end
  end
  
  def self.authenticate(email, password)    
    user = User.find_by_email(email)
    if user
      if user.password == user.make_hash(password, user.salt)
        return user
      else
        return false
      end
    else 
      return false
    end
  end
  
  def make_salt(email)
    #First create some salt
    Digest::SHA1.hexdigest("Use #{email} with #{Time.now} to make salt")
  end

  def make_hash(password, salt)
    if password.present?   
      #Then merge the salt and the password together
      Digest::SHA1.hexdigest("Put #{salt} on the #{password}")
    end
  end

  def create_hashed_password
    unless password.empty? && !confirmed      
      self.salt = self.make_salt(email) if salt.blank?
      self.password = self.make_hash(password, salt)
    end  
  end
  
end